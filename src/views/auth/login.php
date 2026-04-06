<?php
$errorMessage = flash('error');
$successMessage = flash('success');
?>

<section class="auth-wrapper">
    <div class="auth-card">
        <h1>Dang nhap</h1>
        <p>Su dung email va mat khau de tiep tuc dat hang.</p>

        <?php if ($errorMessage): ?>
            <div class="alert alert-error"><?php echo e($errorMessage); ?></div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo e($successMessage); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route_url('login')); ?>" class="auth-form">
            <label>
                <span>Email</span>
                <input type="email" name="email" required>
            </label>

            <label>
                <span>Mat khau</span>
                <input type="password" name="password" required>
            </label>

            <button type="submit" class="button button-primary">Dang nhap</button>
        </form>

        <p>Chua co tai khoan? <a href="<?php echo e(route_url('register')); ?>">Dang ky ngay</a></p>
    </div>
</section>
