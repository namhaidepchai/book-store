<section class="auth-wrapper">
    <div class="auth-card">
        <h1>Admin Register</h1>
        <p>Tao tai khoan admin moi.</p>
        <?php if ($message = flash('error')): ?><div class="alert alert-error"><?php echo e($message); ?></div><?php endif; ?>
        <form method="POST" action="<?php echo e(route_url('admin-register')); ?>" class="auth-form">
            <label><span>Ten</span><input type="text" name="name" required></label>
            <label><span>Email</span><input type="email" name="email" required></label>
            <label><span>Mat khau</span><input type="password" name="password" required></label>
            <label><span>Xac nhan</span><input type="password" name="password_confirmation" required></label>
            <button type="submit" class="button button-primary">Dang ky admin</button>
        </form>
    </div>
</section>
