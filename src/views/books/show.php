<section class="book-detail">
    <div class="book-detail__image">
        <img src="<?php echo e($book['image']); ?>" alt="<?php echo e($book['title']); ?>">
    </div>

    <div class="book-detail__content">
        <?php if ($message = flash('success')): ?><div class="alert alert-success"><?php echo e($message); ?></div><?php endif; ?>
        <?php if ($message = flash('error')): ?><div class="alert alert-error"><?php echo e($message); ?></div><?php endif; ?>
        <p class="eyebrow"><?php echo e($book['category_name']); ?></p>
        <h1><?php echo e($book['title']); ?></h1>
        <p class="book-detail__author">Tac gia: <?php echo e($book['author_name']); ?></p>
        <div class="book-detail__price"><?php echo e(format_price((float) $book['price'])); ?></div>
        <p class="book-detail__description"><?php echo e($book['description']); ?></p>

        <form method="POST" action="<?php echo e(route_url('cart-add')); ?>" class="add-to-cart-form">
            <input type="hidden" name="book_id" value="<?php echo (int) $book['id']; ?>">
            <label><span>So luong</span><input type="number" name="quantity" value="1" min="1"></label>
            <button type="submit" class="button button-primary">Them vao gio hang</button>
        </form>

        <?php if (is_logged_in() && current_user()['role'] === 'user'): ?>
            <div class="button-row top-gap">
                <?php if ($inWishlist): ?>
                    <a class="button button-secondary" href="<?php echo e(route_url('wishlist-remove', ['book_id' => (int) $book['id']])); ?>">Bo yeu thich</a>
                <?php else: ?>
                    <a class="button button-secondary" href="<?php echo e(route_url('wishlist-add', ['book_id' => (int) $book['id']])); ?>">Them wishlist</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section two-column-section">
    <div>
        <div class="section__header"><h2>Danh gia tu nguoi dung</h2><p>Thong tin review hien thi theo du lieu trong database.</p></div>
        <?php if ($reviews): ?>
            <div class="review-list">
                <?php foreach ($reviews as $review): ?>
                    <article class="review-card">
                        <div class="review-card__top"><strong><?php echo e($review['user_name']); ?></strong><span><?php echo str_repeat('*', (int) $review['rating']); ?></span></div>
                        <p><?php echo e($review['content']); ?></p>
                        <small><?php echo e(date('d/m/Y', strtotime((string) $review['created_at']))); ?></small>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state"><h3>Chua co danh gia</h3><p>Tua sach nay chua co nhan xet nao tu nguoi dung.</p></div>
        <?php endif; ?>

        <?php if (is_logged_in() && current_user()['role'] === 'user' && $canReview): ?>
            <form method="POST" action="<?php echo e(route_url('book-review')); ?>" class="admin-form narrow-form section">
                <input type="hidden" name="book_id" value="<?php echo (int) $book['id']; ?>">
                <label><span>So sao</span><select name="rating"><?php for ($i = 5; $i >= 1; $i--): ?><option value="<?php echo $i; ?>"><?php echo $i; ?> sao</option><?php endfor; ?></select></label>
                <label><span>Noi dung danh gia</span><textarea name="content" rows="4" required></textarea></label>
                <button type="submit" class="button button-primary">Gui danh gia</button>
            </form>
        <?php endif; ?>
    </div>

    <div>
        <div class="section__header"><h2>Binh luan</h2><p>Thao luan ve cuon sach voi nguoi dung khac.</p></div>
        <?php if (is_logged_in() && current_user()['role'] === 'user'): ?>
            <form method="POST" action="<?php echo e(route_url('book-comment')); ?>" class="admin-form narrow-form">
                <input type="hidden" name="book_id" value="<?php echo (int) $book['id']; ?>">
                <label><span>Noi dung binh luan</span><textarea name="content" rows="4" required></textarea></label>
                <button type="submit" class="button button-primary">Gui binh luan</button>
            </form>
        <?php endif; ?>

        <div class="review-list section">
            <?php foreach ($comments as $comment): ?>
                <article class="review-card">
                    <div class="review-card__top"><strong><?php echo e($comment['user_name']); ?></strong></div>
                    <p><?php echo e($comment['content']); ?></p>
                    <small><?php echo e(date('d/m/Y H:i', strtotime((string) $comment['created_at']))); ?></small>
                </article>
            <?php endforeach; ?>
            <?php if (!$comments): ?><div class="empty-state"><p>Chua co binh luan nao.</p></div><?php endif; ?>
        </div>
    </div>
</section>
