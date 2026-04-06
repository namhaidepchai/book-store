<?php if ($message = flash('success')): ?><div class="alert alert-success"><?php echo e($message); ?></div><?php endif; ?>
<section class="section">
    <div class="section__header"><h1>Don hang cua toi</h1><p>Theo doi cac don hang da dat.</p></div>
    <table>
        <thead><tr><th>Ma don</th><th>Tong tien</th><th>Trang thai</th><th>Ngay tao</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo (int) $order['id']; ?></td>
                    <td><?php echo e(format_price((float) $order['total_amount'])); ?></td>
                    <td><?php echo e($order['status']); ?></td>
                    <td><?php echo e($order['created_at']); ?></td>
                    <td class="table-actions">
                        <a href="<?php echo e(route_url('my-orders-show', ['id' => (int) $order['id']])); ?>">Chi tiet</a>
                        <?php if ($order['status'] === 'pending'): ?>
                            <a href="<?php echo e(route_url('my-orders-cancel', ['id' => (int) $order['id']])); ?>">Huy don</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
