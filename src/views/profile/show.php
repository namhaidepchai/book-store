<?php if ($message = flash('success')): ?><div class="alert alert-success"><?php echo e($message); ?></div><?php endif; ?>
<?php if ($message = flash('error')): ?><div class="alert alert-error"><?php echo e($message); ?></div><?php endif; ?>
<section class="section">
    <div class="section__header"><h1>Ho so ca nhan</h1><p>Xem va cap nhat thong tin tai khoan.</p></div>
    <div class="detail-grid">
        <div class="detail-card">
            <p><strong>Ten:</strong> <?php echo e($user['name'] ?? ''); ?></p>
            <p><strong>Email:</strong> <?php echo e($user['email'] ?? ''); ?></p>
            <p><strong>So dien thoai:</strong> <?php echo e($user['phone'] ?? ''); ?></p>
            <p><strong>Dia chi:</strong> <?php echo e($user['address'] ?? ''); ?></p>
            <p><a href="<?php echo e(route_url('change-password')); ?>">Doi mat khau</a></p>
        </div>
        <form method="POST" action="<?php echo e(route_url('profile-update')); ?>" class="admin-form">
            <label><span>Ten</span><input type="text" name="name" value="<?php echo e($user['name'] ?? ''); ?>" required></label>
            <label><span>Email</span><input type="email" name="email" value="<?php echo e($user['email'] ?? ''); ?>" required></label>
            <label><span>So dien thoai</span><input type="text" name="phone" value="<?php echo e($user['phone'] ?? ''); ?>"></label>
            <label><span>Dia chi</span><textarea name="address" rows="4"><?php echo e($user['address'] ?? ''); ?></textarea></label>
            <button type="submit" class="button button-primary">Cap nhat ho so</button>
        </form>
    </div>
</section>
