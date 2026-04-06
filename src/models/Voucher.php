<?php

declare(strict_types=1);

class Voucher
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM vouchers ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM vouchers WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $voucher = $stmt->fetch();
        return $voucher ?: null;
    }

    public function findValidByCode(string $code): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM vouchers
             WHERE code = :code
               AND start_date <= CURDATE()
               AND end_date >= CURDATE()
               AND usage_limit > used_count
             LIMIT 1"
        );
        $stmt->execute(['code' => strtoupper($code)]);
        $voucher = $stmt->fetch();
        return $voucher ?: null;
    }

    public function incrementUsage(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE vouchers SET used_count = used_count + 1 WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO vouchers (code, discount_type, discount_value, start_date, end_date, usage_limit, used_count, created_at)
             VALUES (:code, :discount_type, :discount_value, :start_date, :end_date, :usage_limit, 0, NOW())'
        );

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $stmt = $this->pdo->prepare(
            'UPDATE vouchers
             SET code = :code,
                 discount_type = :discount_type,
                 discount_value = :discount_value,
                 start_date = :start_date,
                 end_date = :end_date,
                 usage_limit = :usage_limit
             WHERE id = :id'
        );

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM vouchers WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
