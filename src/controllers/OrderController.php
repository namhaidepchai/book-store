<?php

declare(strict_types=1);

class OrderController extends Controller
{
    public function checkout(): void
    {
        if (!is_logged_in()) {
            flash('error', 'Ban can dang nhap truoc khi dat hang.');
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cart');
        }

        $cartRows = (new Cart($this->pdo))->items((int) current_user()['id']);
        if ($cartRows === []) {
            flash('error', 'Gio hang dang trong.');
            $this->redirect('cart');
        }

        $items = [];
        $total = 0.0;
        foreach ($cartRows as $row) {
            $items[] = [
                'book_id' => $row['book_id'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
            ];
            $total += (float) $row['price'] * (int) $row['quantity'];
        }

        $user = current_user();
        $userData = (new User($this->pdo))->find((int) $user['id']);
        $customer = [
            'user_id' => $user['id'],
            'customer_name' => trim((string) ($_POST['customer_name'] ?? $userData['name'] ?? $user['name'])),
            'customer_email' => trim((string) ($_POST['customer_email'] ?? $userData['email'] ?? $user['email'])),
            'customer_phone' => trim((string) ($_POST['customer_phone'] ?? $userData['phone'] ?? '')),
            'customer_address' => trim((string) ($_POST['customer_address'] ?? $userData['address'] ?? '')),
        ];

        if ($customer['customer_name'] === '' || $customer['customer_email'] === '' || $customer['customer_address'] === '') {
            flash('error', 'Vui long nhap day du thong tin dat hang.');
            $this->redirect('cart');
        }

        $voucher = null;
        $voucherCode = trim((string) ($_POST['voucher_code'] ?? ''));
        if ($voucherCode !== '') {
            $voucher = (new Voucher($this->pdo))->findValidByCode($voucherCode);
            if (!$voucher) {
                flash('error', 'Voucher khong hop le hoac da het han.');
                $this->redirect('cart');
            }
        }

        $orderId = (new Order($this->pdo))->create($customer, $items, $total, $voucher);
        (new Cart($this->pdo))->clear((int) $user['id']);
        flash('success', 'Dat hang thanh cong. Ma don hang cua ban la #' . $orderId . '.');
        $this->redirect('my-orders');
    }
}
