<?php

declare(strict_types=1);

abstract class AdminController extends Controller
{
    protected function requireAdmin(): void
    {
        if (!is_admin()) {
            flash('error', 'Ban can dang nhap admin de tiep tuc.');
            $this->redirect('admin-login');
        }
    }

    protected function renderAdmin(string $view, array $data = []): void
    {
        $this->requireAdmin();
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException('View not found: ' . $view);
        }

        require __DIR__ . '/../layouts/admin/header.php';
        require $viewFile;
        require __DIR__ . '/../layouts/admin/footer.php';
    }
}
