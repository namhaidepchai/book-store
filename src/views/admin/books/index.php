<section class="section">
    <div class="section__header admin-section-head">
        <div>
            <h1>Quan ly sach</h1>
            <p>Them, sua, xoa san pham sach.</p>
        </div>
        <a class="button button-primary" href="<?php echo e(route_url('admin-books-create')); ?>">Them sach</a>
    </div>
    <table>
        <thead><tr><th>Ten sach</th><th>Tac gia</th><th>Danh muc</th><th>Gia</th><th>Ton kho</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?php echo e($book['title']); ?></td>
                <td><?php echo e($book['author_name']); ?></td>
                <td><?php echo e($book['category_name']); ?></td>
                <td><?php echo e(format_price((float) $book['price'])); ?></td>
                <td><?php echo (int) $book['stock_quantity']; ?></td>
                <td class="table-actions">
                    <a href="<?php echo e(route_url('admin-books-edit', ['id' => (int) $book['id']])); ?>">Sua</a>
                    <a href="<?php echo e(route_url('admin-books-delete', ['id' => (int) $book['id']])); ?>" onclick="return confirm('Xoa sach nay?')">Xoa</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
