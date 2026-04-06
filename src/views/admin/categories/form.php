<section class="section">
    <div class="section__header"><h1><?php echo e($category ? 'Sua danh muc' : 'Them danh muc'); ?></h1></div>
    <form method="POST" action="<?php echo e($formAction); ?>" class="admin-form narrow-form">
        <label><span>Ten danh muc</span><input type="text" name="name" value="<?php echo e($category['name'] ?? ''); ?>" required></label>
        <button type="submit" class="button button-primary">Luu danh muc</button>
    </form>
</section>
