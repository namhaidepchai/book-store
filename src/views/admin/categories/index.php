<section class="section">
    <div class="section__header admin-section-head">
        <div><h1>Quan ly danh muc</h1><p>Them, sua, xoa danh muc sach.</p></div>
        <a class="button button-primary" href="<?php echo e(route_url('admin-categories-create')); ?>">Them danh muc</a>
    </div>
    <table>
        <thead><tr><th>Ten</th><th>Slug</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo e($category['name']); ?></td>
                <td><?php echo e($category['slug']); ?></td>
                <td class="table-actions">
                    <a href="<?php echo e(route_url('admin-categories-edit', ['id' => (int) $category['id']])); ?>">Sua</a>
                    <a href="<?php echo e(route_url('admin-categories-delete', ['id' => (int) $category['id']])); ?>" onclick="return confirm('Xoa danh muc nay?')">Xoa</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
