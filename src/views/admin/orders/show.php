<section class="section">
    <div class="section__header"><h1>Chi tiet don hang #<?php echo (int) ($order['id'] ?? 0); ?></h1></div>
    <?php if ($order): ?>
        <div class="detail-grid">
            <div class="detail-card">
                <p><strong>Khach:</strong> <?php echo e($order['customer_name']); ?></p>
                <p><strong>Email:</strong> <?php echo e($order['customer_email']); ?></p>
                <p><strong>So dien thoai:</strong> <?php echo e($order['customer_phone']); ?></p>
                <p><strong>Dia chi:</strong> <?php echo e($order['customer_address']); ?></p>
                <p><strong>Tong tien:</strong> <?php echo e(format_price((float) $order['total_amount'])); ?></p>
            </div>
            <div class="detail-card">
                <form method="POST" action="<?php echo e(route_url('admin-orders-update-status')); ?>" class="admin-form narrow-form">
                    <input type="hidden" name="id" value="<?php echo (int) $order['id']; ?>">
                    <label><span>Trang thai</span>
                        <select name="status">
                            <?php foreach (['pending' => 'Cho xac nhan', 'confirmed' => 'Da xac nhan', 'shipping' => 'Dang giao', 'completed' => 'Hoan thanh', 'cancelled' => 'Da huy'] as $value => $label): ?>
                                <option value="<?php echo e($value); ?>" <?php echo $order['status'] === $value ? 'selected' : ''; ?>><?php echo e($label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <button type="submit" class="button button-primary">Cap nhat trang thai</button>
                </form>
            </div>
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
