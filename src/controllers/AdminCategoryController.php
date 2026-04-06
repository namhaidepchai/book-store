<?php

declare(strict_types=1);

class AdminCategoryController extends AdminController
{
    public function index(): void
    {
        $model = new Category($this->pdo);
        $this->renderAdmin('admin/categories/index', [
            'pageTitle' => 'Quan ly danh muc',
            'categories' => $model->all(),
        ]);
    }

    public function create(): void
    {
        $model = new Category($this->pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->create(trim((string) ($_POST['name'] ?? '')));
            flash('success', 'Da them danh muc.');
            $this->redirect('admin-categories');
        }

        $this->renderAdmin('admin/categories/form', [
            'pageTitle' => 'Them danh muc',
            'category' => null,
            'formAction' => route_url('admin-categories-create'),
        ]);
    }

    public function edit(): void
    {
        $model = new Category($this->pdo);
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->update($id, trim((string) ($_POST['name'] ?? '')));
            flash('success', 'Da cap nhat danh muc.');
            $this->redirect('admin-categories');
        }

        $this->renderAdmin('admin/categories/form', [
            'pageTitle' => 'Sua danh muc',
            'category' => $model->find($id),
            'formAction' => route_url('admin-categories-edit', ['id' => $id]),
        ]);
    }

    public function delete(): void
    {
        (new Category($this->pdo))->delete((int) ($_GET['id'] ?? 0));
        flash('success', 'Da xoa danh muc.');
        $this->redirect('admin-categories');
    }
}
