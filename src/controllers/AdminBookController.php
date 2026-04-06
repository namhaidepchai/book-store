<?php

declare(strict_types=1);

class AdminBookController extends AdminController
{
    public function index(): void
    {
        $bookModel = new Book($this->pdo);
        $this->renderAdmin('admin/books/index', [
            'pageTitle' => 'Quan ly sach',
            'books' => $bookModel->adminAll(),
        ]);
    }

    public function create(): void
    {
        $bookModel = new Book($this->pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookModel->create($this->payload());
            flash('success', 'Da them sach moi.');
            $this->redirect('admin-books');
        }

        $this->renderAdmin('admin/books/form', [
            'pageTitle' => 'Them sach',
            'book' => null,
            'filters' => $bookModel->getFilters(),
            'formAction' => route_url('admin-books-create'),
        ]);
    }

    public function edit(): void
    {
        $bookModel = new Book($this->pdo);
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookModel->update($id, $this->payload());
            flash('success', 'Da cap nhat sach.');
            $this->redirect('admin-books');
        }

        $this->renderAdmin('admin/books/form', [
            'pageTitle' => 'Sua sach',
            'book' => $bookModel->find($id),
            'filters' => $bookModel->getFilters(),
            'formAction' => route_url('admin-books-edit', ['id' => $id]),
        ]);
    }

    public function delete(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        (new Book($this->pdo))->delete($id);
        flash('success', 'Da xoa sach.');
        $this->redirect('admin-books');
    }

    private function payload(): array
    {
        return [
            'title' => trim((string) ($_POST['title'] ?? '')),
            'author_id' => (int) ($_POST['author_id'] ?? 0),
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'price' => (float) ($_POST['price'] ?? 0),
            'description' => trim((string) ($_POST['description'] ?? '')),
            'image' => trim((string) ($_POST['image'] ?? '')),
            'stock_quantity' => (int) ($_POST['stock_quantity'] ?? 0),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
        ];
    }
}
