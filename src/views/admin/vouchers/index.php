<section class="section">
    <div class="section__header admin-section-head">
        <div><h1>Quan ly voucher</h1><p>Tao va quan ly voucher giam gia.</p></div>
        <a class="button button-primary" href="<?php echo e(route_url('admin-vouchers-create')); ?>">Them voucher</a>
    </div>
    <table>
        <thead><tr><th>Ma</th><th>Kieu</th><th>Gia tri</th><th>Bat dau</th><th>Ket thuc</th><th>So lan dung</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($vouchers as $voucher): ?>
            <tr>
                <td><?php echo e($voucher['code']); ?></td>
                <td><?php echo e($voucher['discount_type']); ?></td>
                <td><?php echo e((string) $voucher['discount_value']); ?></td>
                <td><?php echo e($voucher['start_date']); ?></td>
                <td><?php echo e($voucher['end_date']); ?></td>
                <td><?php echo (int) $voucher['usage_limit']; ?></td>
                <td class="table-actions">
                    <a href="<?php echo e(route_url('admin-vouchers-edit', ['id' => (int) $voucher['id']])); ?>">Sua</a>
                    <a href="<?php echo e(route_url('admin-vouchers-delete', ['id' => (int) $voucher['id']])); ?>" onclick="return confirm('Xoa voucher nay?')">Xoa</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
