<?php

declare(strict_types=1);

class WishlistController extends Controller
{
    public function index(): void
    {
        if (!is_logged_in()) {
            $this->redirect('login');
        }

        $items = (new Wishlist($this->pdo))->all((int) current_user()['id']);
        $this->render('wishlist/index', [
            'pageTitle' => 'Wishlist',
            'items' => $items,
        ]);
    }

    public function add(): void
    {
        if (!is_logged_in()) {
            flash('error', 'Ban can dang nhap de them wishlist.');
            $this->redirect('login');
        }

        $bookId = (int) (($_POST['book_id'] ?? $_GET['book_id']) ?? 0);
        if ($bookId > 0) {
            (new Wishlist($this->pdo))->add((int) current_user()['id'], $bookId);
            flash('success', 'Da them vao wishlist.');
        }

        $this->redirect('wishlist');
    }

    public function remove(): void
    {
        if (is_logged_in()) {
            (new Wishlist($this->pdo))->remove((int) current_user()['id'], (int) ($_GET['book_id'] ?? $_POST['book_id'] ?? 0));
            flash('success', 'Da xoa khoi wishlist.');
        }

        $this->redirect('wishlist');
    }
}
