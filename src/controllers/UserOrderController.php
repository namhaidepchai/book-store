<?php

declare(strict_types=1);

class UserOrderController extends Controller
{
    public function index(): void
    {
        if (!is_logged_in()) {
            $this->redirect('login');
        }

        $orders = (new Order($this->pdo))->byUser((int) current_user()['id']);
        $this->render('orders/index', [
            'pageTitle' => 'Don hang cua toi',
            'orders' => $orders,
        ]);
    }

    public function show(): void
    {
        if (!is_logged_in()) {
            $this->redirect('login');
        }

        $order = (new Order($this->pdo))->findForUser((int) ($_GET['id'] ?? 0), (int) current_user()['id']);
        $this->render('orders/show', [
            'pageTitle' => 'Chi tiet don hang',
            'order' => $order,
        ]);
    }

    public function cancel(): void
    {
        if (!is_logged_in()) {
            $this->redirect('login');
        }

        (new Order($this->pdo))->cancelForUser((int) ($_GET['id'] ?? 0), (int) current_user()['id']);
        flash('success', 'Da huy don hang neu don dang cho xac nhan.');
        $this->redirect('my-orders');
    }
}
