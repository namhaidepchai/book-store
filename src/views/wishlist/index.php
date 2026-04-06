<?php if ($message = flash('success')): ?><div class="alert alert-success"><?php echo e($message); ?></div><?php endif; ?>
<section class="section">
    <div class="section__header"><h1>Wishlist</h1><p>Danh sach sach yeu thich cua ban.</p></div>
    <?php if ($items): ?>
        <div class="book-grid">
            <?php foreach ($items as $item): ?>
                <article class="book-card">
                    <a class="book-card__image" href="<?php echo e(route_url('book', ['id' => (int) $item['book_id']])); ?>"><img src="<?php echo e($item['image']); ?>" alt="<?php echo e($item['title']); ?>"></a>
                    <div class="book-card__body">
                        <h3><a href="<?php echo e(route_url('book', ['id' => (int) $item['book_id']])); ?>"><?php echo e($item['title']); ?></a></h3>
                        <p class="book-card__author"><?php echo e($item['author_name']); ?></p>
                        <div class="book-card__price"><?php echo e(format_price((float) $item['price'])); ?></div>
                        <a class="button button-secondary" href="<?php echo e(route_url('wishlist-remove', ['book_id' => (int) $item['book_id']])); ?>">Xoa khoi wishlist</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state"><p>Wishlist cua ban dang trong.</p></div>
    <?php endif; ?>
</section>
