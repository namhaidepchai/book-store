<?php

declare(strict_types=1);

class Order
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(array $customer, array $items, float $total, ?array $voucher = null): int
    {
        $this->pdo->beginTransaction();

        try {
            $discountAmount = 0.0;
            $voucherCode = null;
            if ($voucher) {
                $voucherCode = $voucher['code'];
                $discountAmount = $voucher['discount_type'] === 'percent'
                    ? ($total * ((float) $voucher['discount_value'] / 100))
                    : (float) $voucher['discount_value'];
                $discountAmount = min($discountAmount, $total);
            }

            $finalTotal = max(0, $total - $discountAmount);
            $stmt = $this->pdo->prepare(
                'INSERT INTO orders (
                    user_id,
                    customer_name,
                    customer_email,
                    customer_phone,
                    customer_address,
                    voucher_code,
                    discount_amount,
                    total_amount,
                    status,
                    created_at
                ) VALUES (
                    :user_id,
                    :customer_name,
                    :customer_email,
                    :customer_phone,
                    :customer_address,
                    :voucher_code,
                    :discount_amount,
                    :total_amount,
                    :status,
                    NOW()
                )'
            );

            $stmt->execute([
                'user_id' => $customer['user_id'],
                'customer_name' => $customer['customer_name'],
                'customer_email' => $customer['customer_email'],
                'customer_phone' => $customer['customer_phone'],
                'customer_address' => $customer['customer_address'],
                'voucher_code' => $voucherCode,
                'discount_amount' => $discountAmount,
                'total_amount' => $finalTotal,
                'status' => 'pending',
            ]);

            $orderId = (int) $this->pdo->lastInsertId();
            $itemStmt = $this->pdo->prepare(
                'INSERT INTO order_items (order_id, book_id, quantity, unit_price)
                 VALUES (:order_id, :book_id, :quantity, :unit_price)'
            );
            $stockStmt = $this->pdo->prepare('UPDATE books SET stock_quantity = stock_quantity - :quantity, sold_count = sold_count + :quantity WHERE id = :book_id');

            foreach ($items as $item) {
                $itemStmt->execute([
                    'order_id' => $orderId,
                    'book_id' => $item['book_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);
                $stockStmt->execute([
                    'quantity' => $item['quantity'],
                    'book_id' => $item['book_id'],
                ]);
            }

            if ($voucher) {
                (new Voucher($this->pdo))->incrementUsage((int) $voucher['id']);
            }

            $this->pdo->commit();
            return $orderId;
        } catch (Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }
    }

    public function all(array $filters = []): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['status'])) {
            $conditions[] = 'o.status = :status';
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['customer'])) {
            $conditions[] = '(o.customer_name LIKE :customer OR o.customer_email LIKE :customer)';
            $params['customer'] = '%' . $filters['customer'] . '%';
        }

        if (!empty($filters['date'])) {
            $conditions[] = 'DATE(o.created_at) = :date';
            $params['date'] = $filters['date'];
        }

        if (!empty($filters['user_id'])) {
            $conditions[] = 'o.user_id = :user_id';
            $params['user_id'] = $filters['user_id'];
        }

        $where = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $sql = 'SELECT o.*, u.name AS user_name
                FROM orders o
                INNER JOIN users u ON u.id = o.user_id' . $where . ' ORDER BY o.created_at DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function history(array $filters = []): array
    {
        $filters['status'] = $filters['status'] ?? '';
        $records = $this->all($filters);
        return array_values(array_filter($records, static fn(array $row): bool => in_array($row['status'], ['completed', 'cancelled'], true)));
    }

    public function byUser(int $userId): array
    {
        return $this->all(['user_id' => $userId]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT o.*, u.name AS user_name FROM orders o INNER JOIN users u ON u.id = o.user_id WHERE o.id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch();
        if (!$order) {
            return null;
        }

        $itemStmt = $this->pdo->prepare(
            'SELECT oi.*, b.title, b.image
             FROM order_items oi
             INNER JOIN books b ON b.id = oi.book_id
             WHERE oi.order_id = :order_id'
        );
        $itemStmt->execute(['order_id' => $id]);
        $order['items'] = $itemStmt->fetchAll();
        return $order;
    }

    public function findForUser(int $orderId, int $userId): ?array
    {
        $order = $this->find($orderId);
        if (!$order || (int) $order['user_id'] !== $userId) {
            return null;
        }
        return $order;
    }

    public function cancelForUser(int $orderId, int $userId): bool
    {
        $this->pdo->beginTransaction();
        try {
            $order = $this->findForUser($orderId, $userId);
            if (!$order || $order['status'] !== 'pending') {
                $this->pdo->rollBack();
                return false;
            }

            $restore = $this->pdo->prepare('UPDATE books SET stock_quantity = stock_quantity + :quantity, sold_count = GREATEST(sold_count - :quantity, 0) WHERE id = :book_id');
            foreach ($order['items'] as $item) {
                $restore->execute(['quantity' => $item['quantity'], 'book_id' => $item['book_id']]);
            }

            $stmt = $this->pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = :id AND user_id = :user_id AND status = 'pending'");
            $result = $stmt->execute(['id' => $orderId, 'user_id' => $userId]);
            $this->pdo->commit();
            return $result;
        } catch (Throwable $throwable) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }

    public function countOrders(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    }

    public function totalRevenue(): float
    {
        return (float) $this->pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status IN ('confirmed', 'shipping', 'completed')")->fetchColumn();
    }

    public function revenueSeries(): array
    {
        $stmt = $this->pdo->query(
            "SELECT DATE(created_at) AS label, COALESCE(SUM(total_amount), 0) AS revenue
             FROM orders
             WHERE status IN ('confirmed', 'shipping', 'completed')
             GROUP BY DATE(created_at)
             ORDER BY DATE(created_at) ASC"
        );
        return $stmt->fetchAll();
    }
}
