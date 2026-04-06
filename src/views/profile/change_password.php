<?php if ($message = flash('error')): ?><div class="alert alert-error"><?php echo e($message); ?></div><?php endif; ?>
<section class="section">
    <div class="section__header"><h1>Doi mat khau</h1></div>
    <form method="POST" action="<?php echo e(route_url('change-password')); ?>" class="admin-form narrow-form">
        <label><span>Mat khau cu</span><input type="password" name="old_password" required></label>
        <label><span>Mat khau moi</span><input type="password" name="new_password" required></label>
        <label><span>Xac nhan mat khau moi</span><input type="password" name="new_password_confirmation" required></label>
        <button type="submit" class="button button-primary">Cap nhat mat khau</button>
    </form>
</section>
