CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'user',
    status VARCHAR(30) NOT NULL DEFAULT 'active',
    phone VARCHAR(30) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    author_id INT NOT NULL,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    stock_quantity INT NOT NULL DEFAULT 0,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    sold_count INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_books_category FOREIGN KEY (category_id) REFERENCES categories(id),
    CONSTRAINT fk_books_author FOREIGN KEY (author_id) REFERENCES authors(id)
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    rating TINYINT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_book FOREIGN KEY (book_id) REFERENCES books(id),
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY uniq_review_user_book (book_id, user_id)
);

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_comments_book FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_cart_book FOREIGN KEY (book_id) REFERENCES books(id),
    UNIQUE KEY uniq_cart_user_book (user_id, book_id)
);

CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_wishlist_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_wishlist_book FOREIGN KEY (book_id) REFERENCES books(id),
    UNIQUE KEY uniq_wishlist_user_book (user_id, book_id)
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    customer_email VARCHAR(160) NOT NULL,
    customer_phone VARCHAR(30) DEFAULT NULL,
    customer_address TEXT NOT NULL,
    voucher_code VARCHAR(50) DEFAULT NULL,
    discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id),
    CONSTRAINT fk_order_items_book FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE IF NOT EXISTS vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_type VARCHAR(20) NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    usage_limit INT NOT NULL DEFAULT 0,
    used_count INT NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

SET @users_role_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'role');
SET @sql := IF(@users_role_exists = 0, "ALTER TABLE users ADD COLUMN role VARCHAR(30) NOT NULL DEFAULT 'user'", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @users_status_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'status');
SET @sql := IF(@users_status_exists = 0, "ALTER TABLE users ADD COLUMN status VARCHAR(30) NOT NULL DEFAULT 'active'", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @users_phone_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'phone');
SET @sql := IF(@users_phone_exists = 0, "ALTER TABLE users ADD COLUMN phone VARCHAR(30) DEFAULT NULL", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @users_address_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'address');
SET @sql := IF(@users_address_exists = 0, "ALTER TABLE users ADD COLUMN address TEXT DEFAULT NULL", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @books_stock_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'books' AND COLUMN_NAME = 'stock_quantity');
SET @sql := IF(@books_stock_exists = 0, "ALTER TABLE books ADD COLUMN stock_quantity INT NOT NULL DEFAULT 0", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @orders_voucher_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'voucher_code');
SET @sql := IF(@orders_voucher_exists = 0, "ALTER TABLE orders ADD COLUMN voucher_code VARCHAR(50) DEFAULT NULL", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @orders_discount_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND COLUMN_NAME = 'discount_amount');
SET @sql := IF(@orders_discount_exists = 0, "ALTER TABLE orders ADD COLUMN discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
SET @vouchers_used_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'vouchers' AND COLUMN_NAME = 'used_count');
SET @sql := IF(@vouchers_used_exists = 0, "ALTER TABLE vouchers ADD COLUMN used_count INT NOT NULL DEFAULT 0", 'SELECT 1'); PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

INSERT INTO categories (name, slug) VALUES
('Van hoc', 'van-hoc'), ('Khoa hoc', 'khoa-hoc'), ('Cong nghe', 'cong-nghe'), ('Kinh doanh', 'kinh-doanh')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO authors (name) VALUES
('Nguyen Nhat Anh'), ('Stephen Hawking'), ('Robert C. Martin'), ('James Clear'), ('Yuval Noah Harari')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO users (name, email, password, role, status, phone, address) VALUES
('Demo User', 'demo@bookstore.local', '$2y$12$wO/UpxbIEByMnCSaE2nE7.0531.oEj9tgepfRzYgeXYMuQA55jRo6', 'user', 'active', '0901000001', '12 Tran Hung Dao, Ha Noi'),
('Le Thu', 'lethu@bookstore.local', '$2y$12$wO/UpxbIEByMnCSaE2nE7.0531.oEj9tgepfRzYgeXYMuQA55jRo6', 'user', 'active', '0901000002', '25 Le Loi, Da Nang'),
('Minh Tran', 'minhtran@bookstore.local', '$2y$12$wO/UpxbIEByMnCSaE2nE7.0531.oEj9tgepfRzYgeXYMuQA55jRo6', 'user', 'active', '0901000003', '88 Dien Bien Phu, HCM'),
('Book Admin', 'admin@bookstore.local', '$2y$12$wO/UpxbIEByMnCSaE2nE7.0531.oEj9tgepfRzYgeXYMuQA55jRo6', 'admin', 'active', NULL, NULL)
ON DUPLICATE KEY UPDATE name = VALUES(name), role = VALUES(role), status = VALUES(status), phone = VALUES(phone), address = VALUES(address);

INSERT INTO books (category_id, author_id, title, slug, description, price, image, stock_quantity, is_featured, sold_count, created_at) VALUES
(1, 1, 'Mat biec', 'mat-biec', 'Tieu thuyet ve tuoi tre, tinh yeu va nhung ky uc day cam xuc.', 95000, 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=700&q=80', 30, 1, 320, '2026-03-01 10:00:00'),
(1, 5, 'Sapiens', 'sapiens', 'Cau chuyen ngan gon va hap dan ve lich su phat trien cua loai nguoi.', 180000, 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?auto=format&fit=crop&w=700&q=80', 24, 1, 520, '2026-03-12 10:00:00'),
(2, 2, 'A Brief History of Time', 'a-brief-history-of-time', 'Tac pham kinh dien giup nguoi doc tiep can vat ly vu tru hoc.', 210000, 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&w=700&q=80', 18, 0, 275, '2026-02-18 10:00:00'),
(3, 3, 'Clean Code', 'clean-code', 'Nhung nguyen tac viet code sach, de bao tri cho lap trinh vien.', 250000, 'https://images.unsplash.com/photo-1511108690759-009324a90311?auto=format&fit=crop&w=700&q=80', 15, 1, 710, '2026-03-25 10:00:00'),
(4, 4, 'Atomic Habits', 'atomic-habits', 'Huong dan xay dung thoi quen tot de cai thien ban than moi ngay.', 175000, 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=700&q=80', 40, 1, 640, '2026-03-29 10:00:00'),
(3, 3, 'The Clean Coder', 'the-clean-coder', 'Ky nang lam viec chuyen nghiep danh cho lap trinh vien.', 225000, 'https://images.unsplash.com/photo-1519682337058-a94d519337bc?auto=format&fit=crop&w=700&q=80', 14, 0, 180, '2026-01-20 10:00:00'),
(4, 5, 'Homo Deus', 'homo-deus', 'Goc nhin ve tuong lai cua nhan loai va cong nghe.', 195000, 'https://images.unsplash.com/photo-1526243741027-444d633d7365?auto=format&fit=crop&w=700&q=80', 16, 0, 260, '2026-03-15 10:00:00'),
(1, 1, 'Cho toi xin mot ve di tuoi tho', 'cho-toi-xin-mot-ve-di-tuoi-tho', 'Tac pham nhe nhang ve ky uc tuoi tho quen thuoc.', 110000, 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=700&q=80', 27, 1, 450, '2026-04-01 10:00:00')
ON DUPLICATE KEY UPDATE title = VALUES(title), price = VALUES(price), sold_count = VALUES(sold_count), stock_quantity = VALUES(stock_quantity);

INSERT INTO reviews (book_id, user_id, rating, content, created_at) VALUES
(1, 1, 5, 'Sach rat cam dong, doc xong van con nhieu du am.', '2026-04-01 08:00:00'),
(1, 2, 4, 'Van phong trong treo, noi dung gan gui.', '2026-04-02 09:30:00'),
(4, 3, 5, 'Rat huu ich cho nguoi moi hoc lap trinh chuyen nghiep.', '2026-04-03 13:20:00'),
(5, 1, 5, 'De ap dung trong cuoc song hang ngay.', '2026-04-04 17:00:00'),
(2, 2, 4, 'Goc nhin lich su rat cuon hut va de suy ngam.', '2026-04-05 11:15:00')
ON DUPLICATE KEY UPDATE content = VALUES(content), rating = VALUES(rating);

INSERT INTO comments (user_id, book_id, content, created_at)
SELECT 1, 1, 'Minh rat thich cach ke chuyen trong cuon nay.', '2026-04-02 08:45:00'
WHERE NOT EXISTS (SELECT 1 FROM comments WHERE user_id = 1 AND book_id = 1 AND content = 'Minh rat thich cach ke chuyen trong cuon nay.');
INSERT INTO comments (user_id, book_id, content, created_at)
SELECT 2, 4, 'Cuon sach nay rat huu ich cho dev moi vao nghe.', '2026-04-05 09:20:00'
WHERE NOT EXISTS (SELECT 1 FROM comments WHERE user_id = 2 AND book_id = 4 AND content = 'Cuon sach nay rat huu ich cho dev moi vao nghe.');

INSERT INTO wishlist (user_id, book_id, created_at)
SELECT 1, 4, '2026-04-04 12:00:00' WHERE NOT EXISTS (SELECT 1 FROM wishlist WHERE user_id = 1 AND book_id = 4);
INSERT INTO wishlist (user_id, book_id, created_at)
SELECT 1, 5, '2026-04-04 12:05:00' WHERE NOT EXISTS (SELECT 1 FROM wishlist WHERE user_id = 1 AND book_id = 5);

INSERT INTO cart (user_id, book_id, quantity, created_at)
SELECT 1, 2, 1, '2026-04-05 10:00:00' WHERE NOT EXISTS (SELECT 1 FROM cart WHERE user_id = 1 AND book_id = 2);
INSERT INTO cart (user_id, book_id, quantity, created_at)
SELECT 1, 7, 2, '2026-04-05 10:05:00' WHERE NOT EXISTS (SELECT 1 FROM cart WHERE user_id = 1 AND book_id = 7);

INSERT INTO vouchers (code, discount_type, discount_value, start_date, end_date, usage_limit, used_count) VALUES
('WELCOME10', 'percent', 10, '2026-04-01', '2026-12-31', 100, 0),
('SAVE50000', 'fixed', 50000, '2026-04-01', '2026-09-30', 50, 0)
ON DUPLICATE KEY UPDATE discount_value = VALUES(discount_value), end_date = VALUES(end_date), usage_limit = VALUES(usage_limit);

INSERT INTO orders (id, user_id, customer_name, customer_email, customer_phone, customer_address, voucher_code, discount_amount, total_amount, status, created_at)
SELECT 1, 1, 'Demo User', 'demo@bookstore.local', '0900000001', '123 Nguyen Trai, HCM', 'WELCOME10', 30000, 240000, 'completed', '2026-04-02 09:00:00'
WHERE NOT EXISTS (SELECT 1 FROM orders WHERE id = 1);
INSERT INTO order_items (order_id, book_id, quantity, unit_price)
SELECT 1, 1, 1, 95000 WHERE NOT EXISTS (SELECT 1 FROM order_items WHERE order_id = 1 AND book_id = 1);
INSERT INTO order_items (order_id, book_id, quantity, unit_price)
SELECT 1, 5, 1, 175000 WHERE NOT EXISTS (SELECT 1 FROM order_items WHERE order_id = 1 AND book_id = 5);

INSERT INTO orders (id, user_id, customer_name, customer_email, customer_phone, customer_address, voucher_code, discount_amount, total_amount, status, created_at)
SELECT 2, 2, 'Le Thu', 'lethu@bookstore.local', '0900000002', '25 Le Loi, Da Nang', NULL, 0, 250000, 'pending', '2026-04-05 14:30:00'
WHERE NOT EXISTS (SELECT 1 FROM orders WHERE id = 2);
INSERT INTO order_items (order_id, book_id, quantity, unit_price)
SELECT 2, 4, 1, 250000 WHERE NOT EXISTS (SELECT 1 FROM order_items WHERE order_id = 2 AND book_id = 4);

DELETE r1 FROM reviews r1 INNER JOIN reviews r2 ON r1.book_id = r2.book_id AND r1.user_id = r2.user_id AND r1.id > r2.id;

