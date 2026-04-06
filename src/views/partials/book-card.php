<article class="book-card">
    <a class="book-card__image" href="<?php echo e(route_url('book', ['id' => (int) $book['id']])); ?>">
        <img src="<?php echo e($book['image']); ?>" alt="<?php echo e($book['title']); ?>">
    </a>
    <div class="book-card__body">
        <span class="book-card__category"><?php echo e($book['category_name']); ?></span>
        <h3>
            <a href="<?php echo e(route_url('book', ['id' => (int) $book['id']])); ?>"><?php echo e($book['title']); ?></a>
        </h3>
        <p class="book-card__author"><?php echo e($book['author_name']); ?></p>
        <div class="book-card__price"><?php echo e(format_price((float) $book['price'])); ?></div>
    </div>
</article>
