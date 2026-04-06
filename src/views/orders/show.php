<section class="section">
    <div class="section__header"><h1>Chi tiet don hang</h1></div>
    <?php if ($order): ?>
        <div class="detail-card">
            <p><strong>Ma don:</strong> #<?php echo (int) $order['id']; ?></p>
            <p><strong>Trang thai:</strong> <?php echo e($order['status']); ?></p>
            <p><strong>Tong tien:</strong> <?php echo e(format_price((float) $order['total_amount'])); ?></p>
            <p><strong>Voucher:</strong> <?php echo e($order['voucher_code'] ?? 'Khong co'); ?></p>
            <p><strong>Giam gia:</strong> <?php echo e(format_price((float) ($order['discount_amount'] ?? 0))); ?></p>
        </div>
        <table>
            <thead><tr><th>Sach</th><th>So luong</th><th>Gia</th></tr></thead>
            <tbody>
            <?php foreach ($order['items'] as $item): ?>
                <tr><td><?php echo e($item['title']); ?></td><td><?php echo (int) $item['quantity']; ?></td><td><?php echo e(format_price((float) $item['unit_price'])); ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state"><p>Khong tim thay don hang.</p></div>
    <?php endif; ?>
</section>
