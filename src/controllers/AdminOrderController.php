<?php

declare(strict_types=1);

class AdminOrderController extends AdminController
{
    public function index(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'customer' => $_GET['customer'] ?? '',
            'date' => $_GET['date'] ?? '',
        ];

        $this->renderAdmin('admin/orders/index', [
            'pageTitle' => 'Quan ly don hang',
            'orders' => (new Order($this->pdo))->all($filters),
            'filters' => $filters,
        ]);
    }

    public function show(): void
    {
        $order = (new Order($this->pdo))->find((int) ($_GET['id'] ?? 0));
        $this->renderAdmin('admin/orders/show', [
            'pageTitle' => 'Chi tiet don hang',
            'order' => $order,
        ]);
    }

    public function updateStatus(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $status = (string) ($_POST['status'] ?? 'pending');
        (new Order($this->pdo))->updateStatus($id, $status);
        flash('success', 'Da cap nhat trang thai don hang.');
        $this->redirect('admin-orders-show', ['id' => $id]);
    }

    public function cancel(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        (new Order($this->pdo))->updateStatus($id, 'cancelled');
        flash('success', 'Da huy don hang.');
        $this->redirect('admin-orders');
    }

    public function history(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'customer' => $_GET['customer'] ?? '',
            'date' => $_GET['date'] ?? '',
        ];
        $this->renderAdmin('admin/orders/history', [
            'pageTitle' => 'Lich su don hang',
            'orders' => (new Order($this->pdo))->history($filters),
            'filters' => $filters,
        ]);
    }
}
