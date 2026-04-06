<section class="section">
    <div class="section__header"><h1>Quan ly nguoi dung</h1><p>Xem, khoa/mo khoa va xoa user.</p></div>
    <table>
        <thead><tr><th>Ten</th><th>Email</th><th>Vai tro</th><th>Trang thai</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo e($user['name']); ?></td>
                <td><?php echo e($user['email']); ?></td>
                <td><?php echo e($user['role']); ?></td>
                <td><?php echo e($user['status']); ?></td>
                <td class="table-actions">
                    <a href="<?php echo e(route_url('admin-users-show', ['id' => (int) $user['id']])); ?>">Xem</a>
                    <?php if ($user['role'] !== 'admin'): ?>
                        <a href="<?php echo e(route_url('admin-users-toggle-status', ['id' => (int) $user['id']])); ?>"><?php echo $user['status'] === 'active' ? 'Khoa' : 'Mo khoa'; ?></a>
                        <a href="<?php echo e(route_url('admin-users-delete', ['id' => (int) $user['id']])); ?>" onclick="return confirm('Xoa user nay?')">Xoa</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
