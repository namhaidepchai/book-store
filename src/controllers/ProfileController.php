<?php

declare(strict_types=1);

class ProfileController extends Controller
{
    public function show(): void
    {
        if (!is_logged_in()) {
            flash('error', 'Ban can dang nhap.');
            $this->redirect('login');
        }

        $user = (new User($this->pdo))->find((int) current_user()['id']);
        $this->render('profile/show', [
            'pageTitle' => 'Ho so ca nhan',
            'user' => $user,
        ]);
    }

    public function update(): void
    {
        if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profile');
        }

        $userId = (int) current_user()['id'];
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $address = trim((string) ($_POST['address'] ?? ''));

        (new User($this->pdo))->updateProfile($userId, $name, $email, $phone, $address);
        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        flash('success', 'Da cap nhat ho so.');
        $this->redirect('profile');
    }

    public function changePassword(): void
    {
        if (!is_logged_in()) {
            $this->redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User($this->pdo);
            $user = $userModel->findByEmail((string) current_user()['email']);
            $oldPassword = (string) ($_POST['old_password'] ?? '');
            $newPassword = (string) ($_POST['new_password'] ?? '');
            $confirm = (string) ($_POST['new_password_confirmation'] ?? '');

            if (!$user || !password_verify($oldPassword, $user['password'])) {
                flash('error', 'Mat khau cu khong dung.');
                $this->redirect('change-password');
            }

            if ($newPassword === '' || $newPassword !== $confirm) {
                flash('error', 'Mat khau moi khong hop le.');
                $this->redirect('change-password');
            }

            $userModel->updatePassword((int) current_user()['id'], $newPassword);
            flash('success', 'Da doi mat khau.');
            $this->redirect('profile');
        }

        $this->render('profile/change_password', ['pageTitle' => 'Doi mat khau']);
    }
}
