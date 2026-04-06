<section class="section">
    <div class="section__header"><h1>Thong tin user</h1></div>
    <?php if ($user): ?>
        <div class="detail-card">
            <p><strong>ID:</strong> <?php echo (int) $user['id']; ?></p>
            <p><strong>Ten:</strong> <?php echo e($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo e($user['email']); ?></p>
            <p><strong>Vai tro:</strong> <?php echo e($user['role']); ?></p>
            <p><strong>Trang thai:</strong> <?php echo e($user['status']); ?></p>
            <p><strong>Ngay tao:</strong> <?php echo e($user['created_at']); ?></p>
        </div>
    <?php else: ?>
        <div class="empty-state"><p>Khong tim thay user.</p></div>
    <?php endif; ?>
</section>
