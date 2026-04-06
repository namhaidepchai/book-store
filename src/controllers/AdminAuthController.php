<?php

declare(strict_types=1);

class AdminAuthController extends Controller
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $userModel = new User($this->pdo);
            $admin = $userModel->findByEmail($email);

            if ($admin && $admin['role'] === 'admin' && $admin['status'] === 'active' && password_verify($password, $admin['password'])) {
                $_SESSION['user'] = [
                    'id' => $admin['id'],
                    'name' => $admin['name'],
                    'email' => $admin['email'],
                    'role' => $admin['role'],
                    'status' => $admin['status'],
                ];
                flash('success', 'Dang nhap admin thanh cong.');
                $this->redirect('admin-dashboard');
            }

            flash('error', 'Thong tin admin khong hop le.');
            $this->redirect('admin-login');
        }

        $this->render('admin/auth/login', ['pageTitle' => 'Admin Login']);
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $confirm = (string) ($_POST['password_confirmation'] ?? '');
            $userModel = new User($this->pdo);

            if ($name === '' || $email === '' || $password === '' || $password !== $confirm) {
                flash('error', 'Thong tin dang ky admin khong hop le.');
                $this->redirect('admin-register');
            }

            if ($userModel->findByEmail($email)) {
                flash('error', 'Email da ton tai.');
                $this->redirect('admin-register');
            }

            $userModel->create($name, $email, $password, 'admin');
            flash('success', 'Tao tai khoan admin thanh cong.');
            $this->redirect('admin-login');
        }

        $this->render('admin/auth/register', ['pageTitle' => 'Admin Register']);
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        flash('success', 'Admin da dang xuat.');
        $this->redirect('admin-login');
    }
}
