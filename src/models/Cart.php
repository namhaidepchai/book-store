<?php

declare(strict_types=1);

class Cart
{
    public function __construct(private PDO $pdo)
    {
    }

    public function items(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.book_id, c.quantity, b.title, b.price, b.image, a.name AS author_name
             FROM cart c
             INNER JOIN books b ON b.id = c.book_id
             INNER JOIN authors a ON a.id = b.author_id
             WHERE c.user_id = :user_id
             ORDER BY c.created_at DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function add(int $userId, int $bookId, int $quantity): void
    {
        $stmt = $this->pdo->prepare('SELECT quantity FROM cart WHERE user_id = :user_id AND book_id = :book_id LIMIT 1');
        $stmt->execute(['user_id' => $userId, 'book_id' => $bookId]);
        $existing = $stmt->fetchColumn();

        if ($existing !== false) {
            $update = $this->pdo->prepare('UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND book_id = :book_id');
            $update->execute(['quantity' => $quantity, 'user_id' => $userId, 'book_id' => $bookId]);
            return;
        }

        $insert = $this->pdo->prepare('INSERT INTO cart (user_id, book_id, quantity, created_at) VALUES (:user_id, :book_id, :quantity, NOW())');
        $insert->execute(['user_id' => $userId, 'book_id' => $bookId, 'quantity' => $quantity]);
    }

    public function updateQuantity(int $userId, int $bookId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($userId, $bookId);
            return;
        }

        $stmt = $this->pdo->prepare('UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND book_id = :book_id');
        $stmt->execute(['quantity' => $quantity, 'user_id' => $userId, 'book_id' => $bookId]);
    }

    public function remove(int $userId, int $bookId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM cart WHERE user_id = :user_id AND book_id = :book_id');
        $stmt->execute(['user_id' => $userId, 'book_id' => $bookId]);
    }

    public function clear(int $userId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM cart WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
    }
}
