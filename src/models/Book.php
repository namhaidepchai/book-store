<?php

declare(strict_types=1);

class Book
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getFilters(): array
    {
        $categoryModel = new Category($this->pdo);
        $authorModel = new Author($this->pdo);

        return [
            'categories' => $categoryModel->all(),
            'authors' => $authorModel->all(),
        ];
    }

    public function featured(int $limit = 8): array
    {
        $sql = $this->baseSelect() . ' ORDER BY b.is_featured DESC, b.created_at DESC LIMIT :limit';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search(array $filters): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['q'])) {
            $conditions[] = 'b.title LIKE :q';
            $params['q'] = '%' . trim((string) $filters['q']) . '%';
        }

        if (!empty($filters['category_id'])) {
            $conditions[] = 'b.category_id = :category_id';
            $params['category_id'] = (int) $filters['category_id'];
        }

        if (!empty($filters['author_id'])) {
            $conditions[] = 'b.author_id = :author_id';
            $params['author_id'] = (int) $filters['author_id'];
        }

        if ($filters['min_price'] !== '' && $filters['min_price'] !== null) {
            $conditions[] = 'b.price >= :min_price';
            $params['min_price'] = (float) $filters['min_price'];
        }

        if ($filters['max_price'] !== '' && $filters['max_price'] !== null) {
            $conditions[] = 'b.price <= :max_price';
            $params['max_price'] = (float) $filters['max_price'];
        }

        $whereSql = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';
        $orderSql = $this->sortClause($filters['sort'] ?? 'newest');
        $sql = $this->baseSelect() . $whereSql . ' ' . $orderSql;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function adminAll(): array
    {
        $sql = $this->baseSelect() . ' ORDER BY b.created_at DESC';
        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $sql = $this->baseSelect() . ' WHERE b.id = :id LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $book = $stmt->fetch();
        return $book ?: null;
    }

    public function findManyByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = $this->baseSelect() . " WHERE b.id IN ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($ids));

        $rows = $stmt->fetchAll();
        $indexed = [];
        foreach ($rows as $row) {
            $indexed[(int) $row['id']] = $row;
        }
        return $indexed;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO books (category_id, author_id, title, slug, description, price, image, stock_quantity, is_featured, sold_count, created_at)
             VALUES (:category_id, :author_id, :title, :slug, :description, :price, :image, :stock_quantity, :is_featured, 0, NOW())'
        );
        return $stmt->execute([
            'category_id' => $data['category_id'],
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'slug' => slugify($data['title']),
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $data['image'],
            'stock_quantity' => $data['stock_quantity'],
            'is_featured' => $data['is_featured'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE books
             SET category_id = :category_id,
                 author_id = :author_id,
                 title = :title,
                 slug = :slug,
                 description = :description,
                 price = :price,
                 image = :image,
                 stock_quantity = :stock_quantity,
                 is_featured = :is_featured
             WHERE id = :id'
        );
        return $stmt->execute([
            'id' => $id,
            'category_id' => $data['category_id'],
            'author_id' => $data['author_id'],
            'title' => $data['title'],
            'slug' => slugify($data['title']),
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $data['image'],
            'stock_quantity' => $data['stock_quantity'],
            'is_featured' => $data['is_featured'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM books WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function countProducts(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();
    }

    private function baseSelect(): string
    {
        return 'SELECT
                    b.id,
                    b.title,
                    b.slug,
                    b.price,
                    b.description,
                    b.image,
                    b.stock_quantity,
                    b.is_featured,
                    b.sold_count,
                    b.created_at,
                    a.name AS author_name,
                    a.id AS author_id,
                    c.name AS category_name,
                    c.id AS category_id
                FROM books b
                INNER JOIN authors a ON a.id = b.author_id
                INNER JOIN categories c ON c.id = b.category_id';
    }

    private function sortClause(string $sort): string
    {
        return match ($sort) {
            'price_asc' => 'ORDER BY b.price ASC',
            'price_desc' => 'ORDER BY b.price DESC',
            'best_seller' => 'ORDER BY b.sold_count DESC, b.created_at DESC',
            default => 'ORDER BY b.created_at DESC',
        };
    }
}
