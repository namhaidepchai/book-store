<?php

declare(strict_types=1);

class InteractionController extends Controller
{
    public function review(): void
    {
        if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }

        $userId = (int) current_user()['id'];
        $bookId = (int) ($_POST['book_id'] ?? 0);
        $rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
        $content = trim((string) ($_POST['content'] ?? ''));
        $reviewModel = new Review($this->pdo);

        if (!$reviewModel->canReview($userId, $bookId)) {
            flash('error', 'Ban chi co the danh gia sach da mua.');
            $this->redirect('book', ['id' => $bookId]);
        }

        $reviewModel->createOrUpdate($userId, $bookId, $rating, $content);
        flash('success', 'Da gui danh gia.');
        $this->redirect('book', ['id' => $bookId]);
    }

    public function comment(): void
    {
        if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }

        $bookId = (int) ($_POST['book_id'] ?? 0);
        $content = trim((string) ($_POST['content'] ?? ''));
        if ($content !== '') {
            (new Comment($this->pdo))->create((int) current_user()['id'], $bookId, $content);
            flash('success', 'Da them binh luan.');
        }

        $this->redirect('book', ['id' => $bookId]);
    }
}
