<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/helpers.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/AdminController.php';
require_once __DIR__ . '/models/Author.php';
require_once __DIR__ . '/models/Category.php';
require_once __DIR__ . '/models/Book.php';
require_once __DIR__ . '/models/Review.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/Voucher.php';
require_once __DIR__ . '/models/Cart.php';
require_once __DIR__ . '/models/Wishlist.php';
require_once __DIR__ . '/models/Comment.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/BookController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/CartController.php';
require_once __DIR__ . '/controllers/OrderController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/WishlistController.php';
require_once __DIR__ . '/controllers/UserOrderController.php';
require_once __DIR__ . '/controllers/InteractionController.php';
require_once __DIR__ . '/controllers/AdminAuthController.php';
require_once __DIR__ . '/controllers/AdminDashboardController.php';
require_once __DIR__ . '/controllers/AdminBookController.php';
require_once __DIR__ . '/controllers/AdminCategoryController.php';
require_once __DIR__ . '/controllers/AdminUserController.php';
require_once __DIR__ . '/controllers/AdminOrderController.php';
require_once __DIR__ . '/controllers/AdminVoucherController.php';

$route = $_GET['route'] ?? 'home';

$routes = [
    'home' => [HomeController::class, 'index'],
    'book' => [BookController::class, 'show'],
    'login' => [AuthController::class, 'login'],
    'register' => [AuthController::class, 'register'],
    'logout' => [AuthController::class, 'logout'],
    'profile' => [ProfileController::class, 'show'],
    'profile-update' => [ProfileController::class, 'update'],
    'change-password' => [ProfileController::class, 'changePassword'],
    'wishlist' => [WishlistController::class, 'index'],
    'wishlist-add' => [WishlistController::class, 'add'],
    'wishlist-remove' => [WishlistController::class, 'remove'],
    'my-orders' => [UserOrderController::class, 'index'],
    'my-orders-show' => [UserOrderController::class, 'show'],
    'my-orders-cancel' => [UserOrderController::class, 'cancel'],
    'book-review' => [InteractionController::class, 'review'],
    'book-comment' => [InteractionController::class, 'comment'],
    'cart' => [CartController::class, 'index'],
    'cart-add' => [CartController::class, 'add'],
    'cart-update' => [CartController::class, 'update'],
    'cart-remove' => [CartController::class, 'remove'],
    'checkout' => [OrderController::class, 'checkout'],
    'admin-login' => [AdminAuthController::class, 'login'],
    'admin-register' => [AdminAuthController::class, 'register'],
    'admin-logout' => [AdminAuthController::class, 'logout'],
    'admin-dashboard' => [AdminDashboardController::class, 'index'],
    'admin-books' => [AdminBookController::class, 'index'],
    'admin-books-create' => [AdminBookController::class, 'create'],
    'admin-books-edit' => [AdminBookController::class, 'edit'],
    'admin-books-delete' => [AdminBookController::class, 'delete'],
    'admin-categories' => [AdminCategoryController::class, 'index'],
    'admin-categories-create' => [AdminCategoryController::class, 'create'],
    'admin-categories-edit' => [AdminCategoryController::class, 'edit'],
    'admin-categories-delete' => [AdminCategoryController::class, 'delete'],
    'admin-users' => [AdminUserController::class, 'index'],
    'admin-users-show' => [AdminUserController::class, 'show'],
    'admin-users-toggle-status' => [AdminUserController::class, 'toggleStatus'],
    'admin-users-delete' => [AdminUserController::class, 'delete'],
    'admin-orders' => [AdminOrderController::class, 'index'],
    'admin-orders-show' => [AdminOrderController::class, 'show'],
    'admin-orders-update-status' => [AdminOrderController::class, 'updateStatus'],
    'admin-orders-cancel' => [AdminOrderController::class, 'cancel'],
    'admin-orders-history' => [AdminOrderController::class, 'history'],
    'admin-vouchers' => [AdminVoucherController::class, 'index'],
    'admin-vouchers-create' => [AdminVoucherController::class, 'create'],
    'admin-vouchers-edit' => [AdminVoucherController::class, 'edit'],
    'admin-vouchers-delete' => [AdminVoucherController::class, 'delete'],
];

if (!isset($routes[$route])) {
    http_response_code(404);
    echo 'Page not found.';
    exit;
}

[$controllerClass, $method] = $routes[$route];
$controller = new $controllerClass($pdo);
$controller->$method();
