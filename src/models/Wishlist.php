<?php

declare(strict_types=1);

class Wishlist
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT w.book_id, b.title, b.price, b.image, a.name AS author_name
             FROM wishlist w
             INNER JOIN books b ON b.id = w.book_id
             INNER JOIN authors a ON a.id = b.author_id
             WHERE w.user_id = :user_id
             ORDER BY w.created_at DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function add(int $userId, int $bookId): void
    {
        $stmt = $this->pdo->prepare('INSERT IGNORE INTO wishlist (user_id, book_id, created_at) VALUES (:user_id, :book_id, NOW())');
        $stmt->execute(['user_id' => $userId, 'book_id' => $bookId]);
    }

    public function remove(int $userId, int $bookId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM wishlist WHERE user_id = :user_id AND book_id = :book_id');
        $stmt->execute(['user_id' => $userId, 'book_id' => $bookId]);
    }

    public function has(int $userId, int $bookId): bool
    {
        $stmt = $this->pdo->prepare('SELECT 1 FROM wishlist WHERE user_id = :user_id AND book_id = :book_id LIMIT 1');
        $stmt->execute(['user_id' => $userId, 'book_id' => $bookId]);
        return (bool) $stmt->fetchColumn();
    }
}
