<?php

declare(strict_types=1);

class HomeController extends Controller
{
    public function index(): void
    {
        $bookModel = new Book($this->pdo);
        $filters = [
            'q' => $_GET['q'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'author_id' => $_GET['author_id'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest',
        ];

        $books = $bookModel->search($filters);
        $featuredBooks = $filters['q'] === '' && $filters['category_id'] === '' && $filters['author_id'] === ''
            && $filters['min_price'] === '' && $filters['max_price'] === ''
            ? $bookModel->featured()
            : [];

        $this->render('home/index', [
            'pageTitle' => 'Book Store',
            'filters' => $filters,
            'books' => $books,
            'featuredBooks' => $featuredBooks,
            'filterData' => $bookModel->getFilters(),
        ]);
    }
}
