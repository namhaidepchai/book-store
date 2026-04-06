<?php
$successMessage = flash('success');
$errorMessage = flash('error');
$user = current_user();
?>

<section class="section">
    <div class="section__header">
        <h1>Gio hang</h1>
        <p>Quan ly sach da chon truoc khi dat hang.</p>
    </div>

    <?php if ($successMessage): ?><div class="alert alert-success"><?php echo e($successMessage); ?></div><?php endif; ?>
    <?php if ($errorMessage): ?><div class="alert alert-error"><?php echo e($errorMessage); ?></div><?php endif; ?>

    <?php if ($items): ?>
        <div class="cart-layout">
            <div class="cart-table">
                <form method="POST" action="<?php echo e(route_url('cart-update')); ?>">
                    <table>
                        <thead><tr><th>Sach</th><th>Gia</th><th>So luong</th><th>Tam tinh</th><th></th></tr></thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><div class="cart-book"><img src="<?php echo e($item['book']['image']); ?>" alt="<?php echo e($item['book']['title']); ?>"><div><strong><?php echo e($item['book']['title']); ?></strong><p><?php echo e($item['book']['author_name']); ?></p></div></div></td>
                                    <td><?php echo e(format_price((float) $item['book']['price'])); ?></td>
                                    <td><input type="number" name="quantities[<?php echo (int) $item['book']['id']; ?>]" value="<?php echo (int) $item['quantity']; ?>" min="1"></td>
                                    <td><?php echo e(format_price((float) $item['subtotal'])); ?></td>
                                    <td><a class="button button-link" href="<?php echo e(route_url('cart-remove', ['book_id' => (int) $item['book']['id']])); ?>">Xoa</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" class="button button-secondary">Cap nhat gio hang</button>
                </form>
            </div>

            <aside class="checkout-panel">
                <h2>Thong tin dat hang</h2>
                <p class="checkout-panel__total">Tong tien tam tinh: <?php echo e(format_price((float) $total)); ?></p>
                <?php if (!$user): ?>
                    <div class="notice-box"><p>Ban can dang nhap truoc khi dat hang.</p><a class="button button-primary" href="<?php echo e(route_url('login')); ?>">Dang nhap ngay</a></div>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route_url('checkout')); ?>" class="checkout-form">
                        <label><span>Ten nguoi nhan</span><input type="text" name="customer_name" value="<?php echo e($user['name']); ?>" required></label>
                        <label><span>Email</span><input type="email" name="customer_email" value="<?php echo e($user['email']); ?>" required></label>
                        <label><span>So dien thoai</span><input type="text" name="customer_phone"></label>
                        <label><span>Dia chi nhan hang</span><textarea name="customer_address" rows="4" required></textarea></label>
                        <label><span>Voucher</span><input type="text" name="voucher_code" placeholder="Nhap ma voucher neu co"></label>
                        <button type="submit" class="button button-primary">Xac nhan dat hang</button>
                    </form>
                <?php endif; ?>
            </aside>
        </div>
    <?php else: ?>
        <div class="empty-state"><h3>Gio hang dang trong</h3><p>Hay quay lai trang chu de them sach vao gio hang.</p><a class="button button-primary" href="<?php echo e(route_url('home')); ?>">Xem sach</a></div>
    <?php endif; ?>
</section>
