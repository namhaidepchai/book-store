<?php

declare(strict_types=1);

class AdminDashboardController extends AdminController
{
    public function index(): void
    {
        $this->requireAdmin();
        $orderModel = new Order($this->pdo);
        $userModel = new User($this->pdo);
        $bookModel = new Book($this->pdo);

        $this->renderAdmin('admin/dashboard/index', [
            'pageTitle' => 'Admin Dashboard',
            'stats' => [
                'orders' => $orderModel->countOrders(),
                'revenue' => $orderModel->totalRevenue(),
                'users' => $userModel->countUsers(),
                'products' => $bookModel->countProducts(),
            ],
            'revenueSeries' => $orderModel->revenueSeries(),
        ]);
    }
}
