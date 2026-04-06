<?php
$pageTitle = $pageTitle ?? 'Book Store';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body>
<header class="site-header">
    <div class="container topbar">
        <a class="brand" href="<?php echo e(route_url('home')); ?>">Book Store</a>
        <form method="GET" action="/index.php" class="header-search">
            <input type="hidden" name="route" value="home">
            <input type="text" name="q" value="<?php echo e((string) ($_GET['q'] ?? '')); ?>" placeholder="Tim theo ten sach...">
            <button type="submit">Tim</button>
        </form>
        <nav class="main-nav">
            <a href="<?php echo e(route_url('home')); ?>">Trang chu</a>
            <a href="<?php echo e(route_url('cart')); ?>">Gio hang</a>
            <?php if ($user && $user['role'] === 'user'): ?>
                <a href="<?php echo e(route_url('wishlist')); ?>">Wishlist</a>
                <a href="<?php echo e(route_url('my-orders')); ?>">Don hang cua toi</a>
                <a href="<?php echo e(route_url('profile')); ?>">Ho so</a>
            <?php endif; ?>
            <?php if (is_admin()): ?>
                <a href="<?php echo e(route_url('admin-dashboard')); ?>">Admin</a>
            <?php endif; ?>
            <?php if ($user): ?>
                <span class="nav-user">Xin chao, <?php echo e($user['name']); ?></span>
                <a href="<?php echo e($user['role'] === 'admin' ? route_url('admin-logout') : route_url('logout')); ?>">Dang xuat</a>
            <?php else: ?>
                <a href="<?php echo e(route_url('login')); ?>">Dang nhap</a>
                <a href="<?php echo e(route_url('register')); ?>">Dang ky</a>
                <a href="<?php echo e(route_url('admin-login')); ?>">Admin Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="page-shell container">
