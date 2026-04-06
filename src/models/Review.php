<?php

declare(strict_types=1);

class Review
{
    public function __construct(private PDO $pdo)
    {
    }

    public function byBook(int $bookId): array
    {
        $sql = 'SELECT u.name AS user_name, r.rating, r.content, r.created_at
                FROM reviews r
                INNER JOIN users u ON u.id = r.user_id
                WHERE r.book_id = :book_id
                ORDER BY r.created_at DESC';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['book_id' => $bookId]);

        return $stmt->fetchAll();
    }

    public function createOrUpdate(int $userId, int $bookId, int $rating, string $content): bool
    {
        $exists = $this->pdo->prepare('SELECT id FROM reviews WHERE user_id = :user_id AND book_id = :book_id LIMIT 1');
        $exists->execute(['user_id' => $userId, 'book_id' => $bookId]);
        $reviewId = $exists->fetchColumn();

        if ($reviewId) {
            $stmt = $this->pdo->prepare('UPDATE reviews SET rating = :rating, content = :content, created_at = NOW() WHERE id = :id');
            return $stmt->execute(['rating' => $rating, 'content' => $content, 'id' => $reviewId]);
        }

        $stmt = $this->pdo->prepare('INSERT INTO reviews (book_id, user_id, rating, content, created_at) VALUES (:book_id, :user_id, :rating, :content, NOW())');
        return $stmt->execute(['book_id' => $bookId, 'user_id' => $userId, 'rating' => $rating, 'content' => $content]);
    }

    public function canReview(int $userId, int $bookId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1
             FROM orders o
             INNER JOIN order_items oi ON oi.order_id = o.id
             WHERE o.user_id = :user_id AND oi.book_id = :book_id AND o.status IN ('confirmed', 'shipping', 'completed')
             LIMIT 1"
        );
        $stmt->execute(['user_id' => $userId, 'book_id' => $bookId]);
        return (bool) $stmt->fetchColumn();
    }
}
