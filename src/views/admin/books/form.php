<section class="section">
    <div class="section__header"><h1><?php echo e($book ? 'Sua sach' : 'Them sach'); ?></h1></div>
    <form method="POST" action="<?php echo e($formAction); ?>" class="admin-form">
        <label><span>Ten sach</span><input type="text" name="title" value="<?php echo e($book['title'] ?? ''); ?>" required></label>
        <label><span>Tac gia</span><select name="author_id" required><?php foreach ($filters['authors'] as $author): ?><option value="<?php echo (int) $author['id']; ?>" <?php echo isset($book['author_id']) && (int) $book['author_id'] === (int) $author['id'] ? 'selected' : ''; ?>><?php echo e($author['name']); ?></option><?php endforeach; ?></select></label>
        <label><span>Danh muc</span><select name="category_id" required><?php foreach ($filters['categories'] as $category): ?><option value="<?php echo (int) $category['id']; ?>" <?php echo isset($book['category_id']) && (int) $book['category_id'] === (int) $category['id'] ? 'selected' : ''; ?>><?php echo e($category['name']); ?></option><?php endforeach; ?></select></label>
        <label><span>Gia</span><input type="number" name="price" value="<?php echo e(isset($book['price']) ? (string) $book['price'] : ''); ?>" min="0" step="1000" required></label>
        <label><span>Hinh anh</span><input type="text" name="image" value="<?php echo e($book['image'] ?? ''); ?>" required></label>
        <label><span>So luong ton kho</span><input type="number" name="stock_quantity" value="<?php echo e(isset($book['stock_quantity']) ? (string) $book['stock_quantity'] : '0'); ?>" min="0" required></label>
        <label><span>Mo ta</span><textarea name="description" rows="5" required><?php echo e($book['description'] ?? ''); ?></textarea></label>
        <label class="checkbox-inline"><input type="checkbox" name="is_featured" <?php echo !empty($book['is_featured']) ? 'checked' : ''; ?>> <span>Sach noi bat</span></label>
        <button type="submit" class="button button-primary">Luu</button>
    </form>
</section>
