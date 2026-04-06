<?php

declare(strict_types=1);

class Comment
{
    public function __construct(private PDO $pdo)
    {
    }

    public function byBook(int $bookId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.id, c.content, c.created_at, u.name AS user_name
             FROM comments c
             INNER JOIN users u ON u.id = c.user_id
             WHERE c.book_id = :book_id
             ORDER BY c.created_at DESC'
        );
        $stmt->execute(['book_id' => $bookId]);
        return $stmt->fetchAll();
    }

    public function create(int $userId, int $bookId, string $content): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO comments (user_id, book_id, content, created_at) VALUES (:user_id, :book_id, :content, NOW())');
        return $stmt->execute(['user_id' => $userId, 'book_id' => $bookId, 'content' => $content]);
    }
}
