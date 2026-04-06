<?php $errorMessage = flash('error'); ?>

<section class="auth-wrapper">
    <div class="auth-card">
        <h1>Dang ky tai khoan</h1>
        <p>Tao tai khoan de luu thong tin mua hang va dat don.</p>

        <?php if ($errorMessage): ?>
            <div class="alert alert-error"><?php echo e($errorMessage); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route_url('register')); ?>" class="auth-form">
            <label>
                <span>Ten</span>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required>
            </label>

            <label>
                <span>Email</span>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required>
            </label>

            <label>
                <span>Mat khau</span>
                <input type="password" name="password" required>
            </label>

            <label>
                <span>Xac nhan mat khau</span>
                <input type="password" name="password_confirmation" required>
            </label>

            <button type="submit" class="button button-primary">Dang ky</button>
        </form>
    </div>
</section>
