<?php

declare(strict_types=1);

class AuthController extends Controller
{
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            $userModel = new User($this->pdo);
            $user = $userModel->findByEmail($email);

            if ($user && $user['status'] === 'active' && $user['role'] === 'user' && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'status' => $user['status'],
                ];
                flash('success', 'Dang nhap thanh cong.');
                $this->redirect('home');
            }

            flash('error', 'Tai khoan user khong hop le hoac da bi khoa.');
            $this->redirect('login');
        }

        $this->render('auth/login', ['pageTitle' => 'Dang nhap']);
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

            if ($name === '' || $email === '' || $password === '') {
                flash('error', 'Vui long nhap day du thong tin.');
                $this->redirect('register');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Email khong hop le.');
                $this->redirect('register');
            }

            if ($password !== $passwordConfirmation) {
                flash('error', 'Mat khau xac nhan khong khop.');
                $this->redirect('register');
            }

            $userModel = new User($this->pdo);
            if ($userModel->findByEmail($email)) {
                flash('error', 'Email da ton tai.');
                $this->redirect('register');
            }

            $userModel->create($name, $email, $password, 'user');
            flash('success', 'Dang ky thanh cong. Moi ban dang nhap.');
            $this->redirect('login');
        }

        $this->render('auth/register', ['pageTitle' => 'Dang ky']);
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        flash('success', 'Ban da dang xuat.');
        $this->redirect('home');
    }
}
