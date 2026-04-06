<?php
$successMessage = flash('success');
$errorMessage = flash('error');
?>

<?php if ($successMessage): ?>
    <div class="alert alert-success"><?php echo e($successMessage); ?></div>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <div class="alert alert-error"><?php echo e($errorMessage); ?></div>
<?php endif; ?>

<section class="hero">
    <div class="hero__content">
        <p class="eyebrow">Book Store</p>
        <h1>Tim sach hay cho hanh trinh hoc tap va giai tri cua ban</h1>
        <p>Kham pha sach moi, sach noi bat, loc theo danh muc va dat hang nhanh trong mot giao dien PHP thuan don gian.</p>
    </div>
</section>

<?php if ($featuredBooks): ?>
    <section class="section">
        <div class="section__header">
            <h2>Sach noi bat</h2>
            <p>Nhung tua sach duoc quan tam nhieu nhat hien nay.</p>
        </div>
        <div class="book-grid">
            <?php foreach ($featuredBooks as $book): ?>
                <?php require __DIR__ . '/../partials/book-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section class="section listing-layout">
    <aside class="filter-panel">
        <h2>Tim kiem va loc</h2>
        <form method="GET" action="/index.php" class="filter-form">
            <input type="hidden" name="route" value="home">

            <label>
                <span>Ten sach</span>
                <input type="text" name="q" value="<?php echo e((string) $filters['q']); ?>" placeholder="Nhap ten sach">
            </label>

            <label>
                <span>Danh muc</span>
                <select name="category_id">
                    <option value="">Tat ca danh muc</option>
                    <?php foreach ($filterData['categories'] as $category): ?>
                        <option value="<?php echo (int) $category['id']; ?>" <?php echo (string) $filters['category_id'] === (string) $category['id'] ? 'selected' : ''; ?>>
                            <?php echo e($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                <span>Tac gia</span>
                <select name="author_id">
                    <option value="">Tat ca tac gia</option>
                    <?php foreach ($filterData['authors'] as $author): ?>
                        <option value="<?php echo (int) $author['id']; ?>" <?php echo (string) $filters['author_id'] === (string) $author['id'] ? 'selected' : ''; ?>>
                            <?php echo e($author['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <div class="price-row">
                <label>
                    <span>Gia tu</span>
                    <input type="number" name="min_price" value="<?php echo e((string) $filters['min_price']); ?>" min="0" step="1000">
                </label>
                <label>
                    <span>Den</span>
                    <input type="number" name="max_price" value="<?php echo e((string) $filters['max_price']); ?>" min="0" step="1000">
                </label>
            </div>

            <label>
                <span>Sap xep</span>
                <select name="sort">
                    <option value="newest" <?php echo $filters['sort'] === 'newest' ? 'selected' : ''; ?>>Sach moi nhat</option>
                    <option value="best_seller" <?php echo $filters['sort'] === 'best_seller' ? 'selected' : ''; ?>>Sach ban chay</option>
                    <option value="price_asc" <?php echo $filters['sort'] === 'price_asc' ? 'selected' : ''; ?>>Gia tang dan</option>
                    <option value="price_desc" <?php echo $filters['sort'] === 'price_desc' ? 'selected' : ''; ?>>Gia giam dan</option>
                </select>
            </label>

            <button type="submit" class="button button-primary">Ap dung</button>
        </form>

        <div class="category-list">
            <h3>Danh muc sach</h3>
            <ul>
                <?php foreach ($filterData['categories'] as $category): ?>
                    <li><a href="<?php echo e(route_url('home', ['category_id' => (int) $category['id']])); ?>"><?php echo e($category['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </aside>

    <div class="listing-content">
        <div class="section__header">
            <h2>Danh sach sach</h2>
            <p><?php echo count($books); ?> ket qua phu hop voi bo loc hien tai.</p>
        </div>

        <?php if ($books): ?>
            <div class="book-grid">
                <?php foreach ($books as $book): ?>
                    <?php require __DIR__ . '/../partials/book-card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>Khong tim thay sach phu hop</h3>
                <p>Thu doi tu khoa tim kiem hoac dieu chinh bo loc.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
