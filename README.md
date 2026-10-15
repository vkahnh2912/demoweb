# NOVAWEAR — Contemporary Minimalist Fashion E-Commerce

Dự án Website Thương Mại Điện Tử Thời Trang hoàn chỉnh xây dựng bằng **Laravel 12**, **MySQL 8**, **Blade**, **Alpine.js** và **Tailwind CSS**. Lấy cảm hứng bố cục và trải nghiệm người dùng cao cấp từ các thương hiệu thời trang đương đại, dự án sử dụng bộ nhận diện thương hiệu, hình ảnh, sản phẩm và dữ liệu hoàn toàn độc lập mang tên **NOVAWEAR**.

---

## 🌟 Tính Năng Nổi Bật

### 1. Khách Hàng & Trải Nghiệm Mua Sắm (Storefront)
- **Trang chủ hiện đại:** Hero banner slider, danh mục nổi bật, New Arrivals, Best Sellers, Promo Banner, Blog thời trang, đăng ký bản tin newsletter.
- **Cửa hàng (Shop):**
  - Grid sản phẩm linh hoạt, responsive từ mobile (375px) đến màn hình lớn (1920px).
  - Bộ lọc đa chiều: Theo Danh mục, Thương hiệu, Khoảng giá, Kích thước (S, M, L, XL), Màu sắc, Tình trạng kho.
  - Sắp xếp thông minh: Mới nhất, Giá tăng/giảm dần, Bán chạy, Nổi bật.
  - Phân trang chuẩn hóa giữ nguyên query string bộ lọc.
- **Trang chi tiết sản phẩm:**
  - Bộ sưu tập ảnh (Main gallery + thumbnails trực quan).
  - Chọn biến thể trực tiếp (Màu sắc, Kích cỡ) với quản lý tồn kho theo từng biến thể.
  - Thêm vào giỏ hàng hoặc Mua ngay không tải lại trang (AJAX).
  - Hệ thống đánh giá sao (1-5 sao) và nhận xét của khách hàng đã mua sản phẩm.
  - Danh sách sản phẩm liên quan cùng danh mục.
- **Giỏ hàng (Cart) & Mini-Cart Drawer:**
  - Drawer trượt mượt mà (Slide-over Cart) mở ngay khi thêm sản phẩm.
  - Cập nhật số lượng (+ / -) và xóa sản phẩm qua Fetch API / AJAX không gián đoạn trải nghiệm.
  - Áp dụng mã giảm giá (Coupon voucher) trực tiếp tính toán lại tổng tiền.
- **Thanh toán (Checkout):**
  - Nhập thông tin giao hàng: Họ tên, Số điện thoại, Địa chỉ, Tỉnh/Thành, Quận/Huyện, Phường/Xã, Ghi chú.
  - Phương thức thanh toán: COD (Thanh toán khi nhận hàng) hoặc Chuyển khoản ngân hàng.
  - Tạo đơn hàng thực tế vào database, tự động trừ tồn kho và ghi nhận lịch sử giảm giá.
- **Tài khoản người dùng (Account):**
  - Đăng ký, đăng nhập, quên mật khẩu, cập nhật hồ sơ cá nhân.
  - Quản lý lịch sử đơn hàng, xem chi tiết từng đơn đặt hàng.
  - Quản lý sổ địa chỉ giao hàng và danh sách sản phẩm yêu thích (Wishlist).
- **Blog & Nội dung:** Danh mục bài viết phong cách sống, cẩm nang phối đồ, tăng lượt xem thực tế.
- **SEO & Tối ưu:** Canonical URL, Meta tags, OpenGraph, dynamic `/sitemap.xml` và `robots.txt`.

---

### 2. Trang Quản Trị Hệ Thống (Admin Dashboard)
- **Dashboard:** Thống kê tổng doanh thu, số lượng đơn hàng, số khách hàng, tổng sản phẩm và biểu đồ tăng trưởng doanh thu 6 tháng gần nhất.
- **Quản lý Sản phẩm (Product CRUD):** Thêm, sửa, xóa sản phẩm; quản lý biến thể Size/Màu sắc; upload nhiều hình ảnh, chọn ảnh đại diện primary.
- **Quản lý Danh mục & Thương hiệu:** CRUD đầy đủ với hỗ trợ slug tự động.
- **Quản lý Đơn hàng:** Xem chi tiết đơn hàng, khách hàng, sản phẩm đã mua; cập nhật trạng thái đơn (*pending, confirmed, processing, shipping, completed, cancelled*) và trạng thái thanh toán.
- **Quản lý Khách hàng:** Xem hồ sơ người dùng, tổng chi tiêu, lịch sử mua hàng, khóa/mở khóa tài khoản.
- **Quản lý Khuyến mãi (Coupons):** Tạo mã giảm giá theo %, theo số tiền cố định, giới hạn lượt dùng và đơn hàng tối thiểu.
- **Quản lý Banner quảng cáo:** Slide trang chủ, banner khuyến mãi.
- **Quản lý Blog & Đánh giá:** Viết bài viết mới, kiểm duyệt đánh giá của khách hàng.
- **Cấu hình hệ thống (Settings):** Tên website, thông tin liên hệ, phí vận chuyển và miễn phí ship.

---

## 🛠 Yêu Cầu Hệ Thống

- **PHP:** >= 8.2 (với các extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`, `tokenizer`)
- **Composer:** >= 2.x
- **Node.js:** >= 18.x & **npm** >= 9.x
- **MySQL:** >= 8.0 hoặc MariaDB >= 10.4

---

## 🚀 Hướng Dẫn Cài Đặt Chi Tiết

### Bước 1: Mở thư mục dự án
```bash
cd thabero-clone
```

### Bước 2: Cài đặt các gói phụ thuộc PHP (Composer)
```bash
composer install
```

### Bước 3: Cài đặt các gói phụ thuộc Frontend (Node/npm)
```bash
npm install
```

### Bước 4: Tạo tệp cấu hình môi trường (.env)
```bash
# Trên Windows PowerShell hoặc CMD:
copy .env.example .env

# Hoặc trên Linux/macOS:
cp .env.example .env
```

### Bước 5: Cấu hình cơ sở dữ liệu MySQL trong `.env`
Mở file `.env` và kiểm tra cấu hình kết nối database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=thabero_clone
DB_USERNAME=root
DB_PASSWORD=
```
*(Đảm bảo MySQL service của bạn đang chạy và database `thabero_clone` đã được tạo).*

### Bước 6: Khởi tạo Application Key
```bash
php artisan key:generate
```

### Bước 7: Khởi chạy Migrations và nạp Dữ Liệu Mẫu (Seed Data)
Lệnh này sẽ tạo đầy đủ 25 bảng và nạp 50 sản phẩm, 150 hình ảnh, 600 biến thể, 12 đơn hàng mẫu, 10 bài viết blog, 30 đánh giá và 3 voucher giảm giá:
```bash
php artisan migrate:fresh --seed
```

### Bước 8: Biên dịch Frontend Assets
```bash
npm run build
```

### Bước 9: Tạo liên kết Storage (Symlink cho upload ảnh)
```bash
php artisan storage:link
```

### Bước 10: Khởi chạy Server ứng dụng
Mở 2 cửa sổ terminal:

**Terminal 1 — Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 — Vite Dev (nếu muốn live-reload trong lúc phát triển):**
```bash
npm run dev
```

Truy cập website tại: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Tài Khoản Demo

### 1. Tài khoản Quản trị viên (Admin):
- **URL Admin:** `http://127.0.0.1:8000/admin`
- **Email:** `admin@example.com`
- **Mật khẩu:** `password`
- *(Quyền truy cập toàn quyền quản trị)*

### 2. Tài khoản Khách hàng (Customer):
- **Email:** `customer@example.com`
- **Mật khẩu:** `password`

### 3. Mã giảm giá dùng thử (Coupons):
- `WELCOME10`: Giảm 10% (Tối đa 100.000₫ cho đơn từ 200.000₫)
- `NOVA50K`: Giảm thẳng 50.000₫ (Đơn từ 500.000₫)
- `VIP20`: Giảm 20% (Tối đa 300.000₫ cho đơn từ 1.000.000₫)

---

## 🧪 Chạy Kiểm Thử Tự Động (Tests)

Toàn bộ 32 Feature & Unit tests bao gồm xác thực tài khoản, giỏ hàng AJAX, luồng thanh toán đơn hàng, phân quyền Admin đã được thiết lập sẵn:
```bash
php artisan test
```

---

## 📁 Cấu Trúc Thư Mục Chính

```
thabero-clone/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Dashboard, Products, Orders, Customers, Coupons, etc.
│   │   │   ├── AccountController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ProductController.php
│   │   │   ├── ShopController.php
│   │   │   └── WishlistController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/                # Product, Order, Cart, Coupon, Review, etc.
│   └── Services/
│       ├── CartService.php    # Business logic cho giỏ hàng
│       ├── OrderService.php   # Xử lý giao dịch đặt hàng an toàn
│       └── WishlistService.php
├── database/
│   ├── migrations/            # 25 file migrations chuẩn hóa
│   └── seeders/
│       └── DatabaseSeeder.php # Tạo dữ liệu demo phong phú
├── resources/
│   ├── css/                   # Tailwind CSS v4 styling
│   ├── js/                    # Alpine.js & Vanilla Fetch API
│   └── views/
│       ├── admin/             # Giao diện quản trị hiện đại
│       ├── components/        # Blade components tái sử dụng
│       ├── layouts/           # App và Admin layouts
│       ├── shop/              # Trang cửa hàng & bộ lọc
│       └── products/          # Chi tiết sản phẩm
└── tests/
    └── Feature/
        └── EcommerceTest.php  # Automated test suite
```

---

## 🛡️ Bản Quyền & Giấy Phép
Dự án được xây dựng với mục đích học tập và phát triển mã nguồn mở theo giấy phép [MIT](LICENSE). Thương hiệu demo **NOVAWEAR** độc lập và không chứa nội dung bản quyền độc quyền của bất kỳ bên thứ ba nào.
