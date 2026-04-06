<?php

declare(strict_types=1);

class BookController extends Controller
{
    public function show(): void
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $bookModel = new Book($this->pdo);
        $reviewModel = new Review($this->pdo);
        $commentModel = new Comment($this->pdo);
        $book = $bookModel->find($id);

        if ($book === null) {
            http_response_code(404);
            echo 'Book not found.';
            exit;
        }

        $canReview = false;
        $inWishlist = false;
        if (is_logged_in()) {
            $userId = (int) current_user()['id'];
            $canReview = $reviewModel->canReview($userId, $id);
            $inWishlist = (new Wishlist($this->pdo))->has($userId, $id);
        }

        $this->render('books/show', [
            'pageTitle' => $book['title'],
            'book' => $book,
            'reviews' => $reviewModel->byBook($id),
            'comments' => $commentModel->byBook($id),
            'canReview' => $canReview,
            'inWishlist' => $inWishlist,
        ]);
    }
}
