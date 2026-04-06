<?php

declare(strict_types=1);

class AdminUserController extends AdminController
{
    public function index(): void
    {
        $this->renderAdmin('admin/users/index', [
            'pageTitle' => 'Quan ly nguoi dung',
            'users' => (new User($this->pdo))->allUsers(),
        ]);
    }

    public function show(): void
    {
        $user = (new User($this->pdo))->find((int) ($_GET['id'] ?? 0));
        $this->renderAdmin('admin/users/show', [
            'pageTitle' => 'Thong tin user',
            'user' => $user,
        ]);
    }

    public function toggleStatus(): void
    {
        $userModel = new User($this->pdo);
        $id = (int) ($_GET['id'] ?? 0);
        $user = $userModel->find($id);
        if ($user && $user['role'] !== 'admin') {
            $newStatus = $user['status'] === 'active' ? 'blocked' : 'active';
            $userModel->updateStatus($id, $newStatus);
            flash('success', 'Da cap nhat trang thai user.');
        }
        $this->redirect('admin-users');
    }

    public function delete(): void
    {
        (new User($this->pdo))->delete((int) ($_GET['id'] ?? 0));
        flash('success', 'Da xoa user.');
        $this->redirect('admin-users');
    }
}
