<?php

declare(strict_types=1);

abstract class Controller
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException('View not found: ' . $view);
        }

        require __DIR__ . '/../layouts/header.php';
        require $viewFile;
        require __DIR__ . '/../layouts/footer.php';
    }

    protected function redirect(string $route, array $params = []): void
    {
        $query = http_build_query(array_merge(['route' => $route], $params));
        header('Location: /index.php?' . $query);
        exit;
    }
}
