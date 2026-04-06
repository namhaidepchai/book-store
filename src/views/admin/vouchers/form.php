<section class="section">
    <div class="section__header"><h1><?php echo e($voucher ? 'Sua voucher' : 'Them voucher'); ?></h1></div>
    <form method="POST" action="<?php echo e($formAction); ?>" class="admin-form narrow-form">
        <label><span>Ma voucher</span><input type="text" name="code" value="<?php echo e($voucher['code'] ?? ''); ?>" required></label>
        <label><span>Kieu giam</span><select name="discount_type"><option value="percent" <?php echo (($voucher['discount_type'] ?? '') === 'percent') ? 'selected' : ''; ?>>Phan tram</option><option value="fixed" <?php echo (($voucher['discount_type'] ?? '') === 'fixed') ? 'selected' : ''; ?>>So tien co dinh</option></select></label>
        <label><span>Gia tri giam</span><input type="number" step="0.01" name="discount_value" value="<?php echo e(isset($voucher['discount_value']) ? (string) $voucher['discount_value'] : ''); ?>" required></label>
        <label><span>Ngay bat dau</span><input type="date" name="start_date" value="<?php echo e($voucher['start_date'] ?? date('Y-m-d')); ?>" required></label>
        <label><span>Ngay ket thuc</span><input type="date" name="end_date" value="<?php echo e($voucher['end_date'] ?? date('Y-m-d')); ?>" required></label>
        <label><span>So lan su dung</span><input type="number" name="usage_limit" value="<?php echo e(isset($voucher['usage_limit']) ? (string) $voucher['usage_limit'] : '0'); ?>" min="0" required></label>
        <button type="submit" class="button button-primary">Luu voucher</button>
    </form>
</section>
