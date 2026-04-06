<section class="auth-wrapper">
    <div class="auth-card">
        <h1>Admin Login</h1>
        <p>Dang nhap de quan tri cua hang sach.</p>
        <?php if ($message = flash('error')): ?><div class="alert alert-error"><?php echo e($message); ?></div><?php endif; ?>
        <?php if ($message = flash('success')): ?><div class="alert alert-success"><?php echo e($message); ?></div><?php endif; ?>
        <form method="POST" action="<?php echo e(route_url('admin-login')); ?>" class="auth-form">
            <label><span>Email</span><input type="email" name="email" required></label>
            <label><span>Mat khau</span><input type="password" name="password" required></label>
            <button type="submit" class="button button-primary">Dang nhap admin</button>
        </form>
        <p>Chua co tai khoan? <a href="<?php echo e(route_url('admin-register')); ?>">Tao admin</a></p>
    </div>
</section>
