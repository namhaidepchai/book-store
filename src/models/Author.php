<?php

declare(strict_types=1);

class Author
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id, name FROM authors ORDER BY name ASC');
        return $stmt->fetchAll();
    }
}
