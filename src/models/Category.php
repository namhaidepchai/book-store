<?php

declare(strict_types=1);

class Category
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT id, name, slug FROM categories ORDER BY name ASC');
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, slug FROM categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch();

        return $category ?: null;
    }

    public function create(string $name): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO categories (name, slug) VALUES (:name, :slug)');
        return $stmt->execute(['name' => $name, 'slug' => slugify($name)]);
    }

    public function update(int $id, string $name): bool
    {
        $stmt = $this->pdo->prepare('UPDATE categories SET name = :name, slug = :slug WHERE id = :id');
        return $stmt->execute(['id' => $id, 'name' => $name, 'slug' => slugify($name)]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM categories WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}
