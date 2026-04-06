# PHP Book Store with Docker

Du an nay la website ban sach bang PHP thuan theo MVC don gian, chay bang Docker Compose voi:

- PHP + Apache
- MySQL
- phpMyAdmin

## Chuc nang Guest

- Trang chu hien thi sach noi bat va sach moi nhat
- Tim kiem, loc, sap xep sach
- Xem chi tiet sach va danh gia
- Dang ky, dang nhap user
- Them vao gio hang va dat hang

## Chuc nang Admin

- Dang ky va dang nhap admin
- Dashboard thong ke: tong don, doanh thu, user, san pham
- CRUD sach
- CRUD danh muc
- Quan ly user: xem, khoa/mo khoa, xoa
- Quan ly don hang: xem danh sach, chi tiet, cap nhat trang thai, huy don
- Lich su don hang: loc theo ngay, trang thai, khach hang
- CRUD voucher

## Cau truc thu muc

```text
project-root/
|-- db/
|   `-- init.sql
|-- docker-compose.yml
|-- Dockerfile
`-- src/
    |-- index.php
    |-- config/
    |-- controllers/
    |-- core/
    |-- helpers/
    |-- layouts/
    |-- models/
    |-- views/
    `-- assets/
```

## Cac bang database

Schema nam trong [db/init.sql](C:\Users\nguye\Desktop\Bookstore\db\init.sql), gom:

- `categories`
- `authors`
- `books`
- `users`
- `reviews`
- `orders`
- `order_items`
- `vouchers`

## Chay du an

```bash
docker-compose up -d
```

Website:

```text
http://localhost:8080
```

phpMyAdmin:

```text
http://localhost:8082
```

## Thong tin database

- Host trong Docker network: `db`
- Host tu may ban: `127.0.0.1`
- Port trong Docker network: `3306`
- Port tu may ban: `3307`
- Database: `app_db`
- Username: `app_user`
- Password: `app_password`
- Root password: `root_password`

## Tai khoan demo

User:
- Email: `demo@bookstore.local`
- Password: `password123`

Admin:
- Email: `admin@bookstore.local`
- Password: `password123`

## URL admin

- Admin login: `http://localhost:8080/index.php?route=admin-login`
- Admin dashboard: `http://localhost:8080/index.php?route=admin-dashboard`

## Luu y seed data

Neu ban muon nap lai du lieu mau:

```bash
docker-compose exec -T db sh -lc "mysql -uapp_user -papp_password app_db < /docker-entrypoint-initdb.d/init.sql"
```

Hoac reset volume:

```bash
docker-compose down -v
docker-compose up -d
```
