<section class="section">
    <div class="section__header"><h1>Quan ly don hang</h1><p>Theo doi va cap nhat trang thai don hang.</p></div>
    <form method="GET" action="/index.php" class="admin-filters">
        <input type="hidden" name="route" value="admin-orders">
        <input type="date" name="date" value="<?php echo e($filters['date']); ?>">
        <input type="text" name="customer" value="<?php echo e($filters['customer']); ?>" placeholder="Khach hang">
        <select name="status">
            <option value="">Tat ca trang thai</option>
            <?php foreach (['pending' => 'Cho xac nhan', 'confirmed' => 'Da xac nhan', 'shipping' => 'Dang giao', 'completed' => 'Hoan thanh', 'cancelled' => 'Da huy'] as $value => $label): ?>
                <option value="<?php echo e($value); ?>" <?php echo $filters['status'] === $value ? 'selected' : ''; ?>><?php echo e($label); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="button button-secondary">Loc</button>
    </form>
    <table>
        <thead><tr><th>Ma don</th><th>Khach</th><th>Tong tien</th><th>Trang thai</th><th>Ngay tao</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?php echo (int) $order['id']; ?></td>
                <td><?php echo e($order['customer_name']); ?></td>
                <td><?php echo e(format_price((float) $order['total_amount'])); ?></td>
                <td><?php echo e($order['status']); ?></td>
                <td><?php echo e($order['created_at']); ?></td>
                <td class="table-actions">
                    <a href="<?php echo e(route_url('admin-orders-show', ['id' => (int) $order['id']])); ?>">Chi tiet</a>
                    <a href="<?php echo e(route_url('admin-orders-cancel', ['id' => (int) $order['id']])); ?>" onclick="return confirm('Huy don nay?')">Huy don</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
