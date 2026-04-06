<section class="section">
    <div class="section__header"><h1>Lich su don hang</h1><p>Don da hoan thanh hoac da huy.</p></div>
    <form method="GET" action="/index.php" class="admin-filters">
        <input type="hidden" name="route" value="admin-orders-history">
        <input type="date" name="date" value="<?php echo e($filters['date']); ?>">
        <input type="text" name="customer" value="<?php echo e($filters['customer']); ?>" placeholder="Khach hang">
        <select name="status">
            <option value="">Tat ca</option>
            <option value="completed" <?php echo $filters['status'] === 'completed' ? 'selected' : ''; ?>>Hoan thanh</option>
            <option value="cancelled" <?php echo $filters['status'] === 'cancelled' ? 'selected' : ''; ?>>Da huy</option>
        </select>
        <button type="submit" class="button button-secondary">Loc</button>
    </form>
    <table>
        <thead><tr><th>Ma don</th><th>Khach</th><th>Trang thai</th><th>Tong tien</th><th>Ngay</th></tr></thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr><td>#<?php echo (int) $order['id']; ?></td><td><?php echo e($order['customer_name']); ?></td><td><?php echo e($order['status']); ?></td><td><?php echo e(format_price((float) $order['total_amount'])); ?></td><td><?php echo e($order['created_at']); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
