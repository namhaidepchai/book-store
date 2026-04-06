<?php

declare(strict_types=1);

class CartController extends Controller
{
    public function index(): void
    {
        $items = [];
        $total = 0.0;

        if (is_logged_in()) {
            $rows = (new Cart($this->pdo))->items((int) current_user()['id']);
            foreach ($rows as $row) {
                $subtotal = (float) $row['price'] * (int) $row['quantity'];
                $total += $subtotal;
                $items[] = [
                    'book' => [
                        'id' => $row['book_id'],
                        'title' => $row['title'],
                        'price' => $row['price'],
                        'image' => $row['image'],
                        'author_name' => $row['author_name'],
                    ],
                    'quantity' => $row['quantity'],
                    'subtotal' => $subtotal,
                ];
            }
        } else {
            $bookModel = new Book($this->pdo);
            $cart = $_SESSION['cart'] ?? [];
            $bookIds = array_map('intval', array_keys($cart));
            $books = $bookModel->findManyByIds($bookIds);

            foreach ($cart as $bookId => $item) {
                if (!isset($books[(int) $bookId])) {
                    continue;
                }

                $book = $books[(int) $bookId];
                $subtotal = $book['price'] * $item['quantity'];
                $total += $subtotal;
                $items[] = [
                    'book' => $book,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                ];
            }
        }

        $this->render('cart/index', [
            'pageTitle' => 'Gio hang',
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('home');
        }

        $bookId = (int) ($_POST['book_id'] ?? 0);
        $quantity = max(1, (int) ($_POST['quantity'] ?? 1));

        if (is_logged_in()) {
            (new Cart($this->pdo))->add((int) current_user()['id'], $bookId, $quantity);
        } else {
            $_SESSION['cart'] ??= [];
            if (!isset($_SESSION['cart'][$bookId])) {
                $_SESSION['cart'][$bookId] = ['quantity' => 0];
            }
            $_SESSION['cart'][$bookId]['quantity'] += $quantity;
        }

        flash('success', 'Da them sach vao gio hang.');
        $this->redirect('cart');
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cart');
        }

        foreach ($_POST['quantities'] ?? [] as $bookId => $quantity) {
            $quantity = (int) $quantity;
            if (is_logged_in()) {
                (new Cart($this->pdo))->updateQuantity((int) current_user()['id'], (int) $bookId, $quantity);
                continue;
            }

            if ($quantity <= 0) {
                unset($_SESSION['cart'][$bookId]);
                continue;
            }
            $_SESSION['cart'][$bookId]['quantity'] = $quantity;
        }

        flash('success', 'Gio hang da duoc cap nhat.');
        $this->redirect('cart');
    }

    public function remove(): void
    {
        $bookId = (int) (($_POST['book_id'] ?? $_GET['book_id']) ?? 0);
        if ($bookId > 0) {
            if (is_logged_in()) {
                (new Cart($this->pdo))->remove((int) current_user()['id'], $bookId);
            } else {
                unset($_SESSION['cart'][$bookId]);
            }
            flash('success', 'Da xoa sach khoi gio hang.');
        }

        $this->redirect('cart');
    }
}
