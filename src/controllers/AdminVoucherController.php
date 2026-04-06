<?php

declare(strict_types=1);

class AdminVoucherController extends AdminController
{
    public function index(): void
    {
        $this->renderAdmin('admin/vouchers/index', [
            'pageTitle' => 'Quan ly voucher',
            'vouchers' => (new Voucher($this->pdo))->all(),
        ]);
    }

    public function create(): void
    {
        $model = new Voucher($this->pdo);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->create($this->payload());
            flash('success', 'Da tao voucher.');
            $this->redirect('admin-vouchers');
        }

        $this->renderAdmin('admin/vouchers/form', [
            'pageTitle' => 'Them voucher',
            'voucher' => null,
            'formAction' => route_url('admin-vouchers-create'),
        ]);
    }

    public function edit(): void
    {
        $model = new Voucher($this->pdo);
        $id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->update($id, $this->payload());
            flash('success', 'Da cap nhat voucher.');
            $this->redirect('admin-vouchers');
        }

        $this->renderAdmin('admin/vouchers/form', [
            'pageTitle' => 'Sua voucher',
            'voucher' => $model->find($id),
            'formAction' => route_url('admin-vouchers-edit', ['id' => $id]),
        ]);
    }

    public function delete(): void
    {
        (new Voucher($this->pdo))->delete((int) ($_GET['id'] ?? 0));
        flash('success', 'Da xoa voucher.');
        $this->redirect('admin-vouchers');
    }

    private function payload(): array
    {
        return [
            'code' => strtoupper(trim((string) ($_POST['code'] ?? ''))),
            'discount_type' => (string) ($_POST['discount_type'] ?? 'percent'),
            'discount_value' => (float) ($_POST['discount_value'] ?? 0),
            'start_date' => (string) ($_POST['start_date'] ?? date('Y-m-d')),
            'end_date' => (string) ($_POST['end_date'] ?? date('Y-m-d')),
            'usage_limit' => (int) ($_POST['usage_limit'] ?? 0),
        ];
    }
}
