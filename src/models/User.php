<?php

declare(strict_types=1);

class User
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, password, role, status, phone, address, created_at FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, role, status, phone, address, created_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function allUsers(): array
    {
        $stmt = $this->pdo->query('SELECT id, name, email, role, status, phone, address, created_at FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function create(string $name, string $email, string $password, string $role = 'user'): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (name, email, password, role, status, phone, address, created_at) VALUES (:name, :email, :password, :role, :status, :phone, :address, NOW())'
        );

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'status' => 'active',
            'phone' => null,
            'address' => null,
        ]);
    }

    public function updateProfile(int $id, string $name, string $email, string $phone, string $address): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE users SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id'
        );
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
        ]);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $stmt = $this->pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare('UPDATE users SET status = :status WHERE id = :id');
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id AND role <> :role');
        return $stmt->execute(['id' => $id, 'role' => 'admin']);
    }

    public function countUsers(): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
    }
}
