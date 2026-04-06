<?php $admin = current_user(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'Admin'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body class="admin-body">
<header class="site-header admin-header">
    <div class="container topbar">
        <a class="brand" href="<?php echo e(route_url('admin-dashboard')); ?>">Admin Panel</a>
        <nav class="main-nav admin-nav">
            <a href="<?php echo e(route_url('admin-dashboard')); ?>">Dashboard</a>
            <a href="<?php echo e(route_url('admin-books')); ?>">Sach</a>
            <a href="<?php echo e(route_url('admin-categories')); ?>">Danh muc</a>
            <a href="<?php echo e(route_url('admin-users')); ?>">Users</a>
            <a href="<?php echo e(route_url('admin-orders')); ?>">Don hang</a>
            <a href="<?php echo e(route_url('admin-orders-history')); ?>">Lich su</a>
            <a href="<?php echo e(route_url('admin-vouchers')); ?>">Voucher</a>
            <span class="nav-user"><?php echo e($admin['name'] ?? 'Admin'); ?></span>
            <a href="<?php echo e(route_url('admin-logout')); ?>">Dang xuat</a>
        </nav>
    </div>
</header>
<main class="page-shell container admin-shell">
<?php if ($message = flash('success')): ?>
    <div class="alert alert-success"><?php echo e($message); ?></div>
<?php endif; ?>
<?php if ($message = flash('error')): ?>
    <div class="alert alert-error"><?php echo e($message); ?></div>
<?php endif; ?>
