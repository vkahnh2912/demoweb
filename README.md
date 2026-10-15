# ✦ NOVAWEAR - Contemporary Minimalist Fashion E-Commerce

> Hệ thống website thương mại điện tử thời trang đương đại tối giản, lấy cảm hứng từ trải nghiệm người dùng đẳng cấp của Thabero, Zara và Mango, xây dựng hoàn chỉnh trên nền tảng **Laravel 12**, **Tailwind CSS v4**, **Alpine.js** và **MySQL**.

---

## 🌟 Tính Năng Nổi Bật

### 1. Giao Diện & Trải Nghiệm Khách Hàng (Storefront)
- **Thiết kế tối giản & Sang trọng:** Bố cục thanh lịch, typography hiện đại, bảng màu trung tính cao cấp.
- **Tối ưu Mobile tuyệt đối (Mobile First):**
  - **Thanh điều hướng đáy (Sticky Bottom Nav):** 5 nút truy cập nhanh bằng 1 ngón tay (Trang chủ, Cửa hàng, Tìm kiếm, Yêu thích, Giỏ hàng).
  - **Thanh mua hàng dính đáy (Sticky Purchase Bar):** Tự động hiển thị khi cuộn trang chi tiết sản phẩm, kèm nút chọn biến thể và mua ngay tức thì.
  - **Menu trượt (Mobile Drawer):** Phân cấp danh mục dạng Accordion, tìm kiếm nhanh, hỗ trợ gọi hotline 1 chạm.
  - **Thanh lọc dính đỉnh (Sticky Filter Bar):** Bộ lọc đa năng trượt mượt mà (danh mục, kích cỡ, màu sắc trực quan, khoảng giá).
- **Trải nghiệm mua hàng mượt mà:**
  - Mini-cart Slide-over Drawer cập nhật bằng AJAX không cần tải lại trang.
  - Thanh đo tiến trình nhận Miễn Phí Vận Chuyển theo thời gian thực (Free Shipping Bar).
  - Danh sách yêu thích (Wishlist) lưu trữ tức thì.
  - Tìm kiếm sản phẩm thông minh gợi ý tự động (Live Search Modal).
  - Thanh toán 2 cột gọn gàng hỗ trợ ship COD và Chuyển khoản ngân hàng.
  - Áp dụng mã giảm giá / Coupon khuyến mãi trực tiếp tại giỏ hàng và thanh toán.

### 2. Trang Quản Trị Hệ Thống (Admin Panel)
- **Dashboard Quản Trị:** Thống kê doanh thu, đơn hàng, khách hàng mới, sản phẩm bán chạy và biểu đồ tăng trưởng 6 tháng.
- **Quản lý Sản phẩm (Products):** Thêm, sửa, xóa, tìm kiếm, lọc, quản lý nhiều hình ảnh và sinh biến thể Size/Màu sắc tự động.
- **Quản lý Danh mục & Thương hiệu (Categories & Brands):** CRUD đầy đủ, tự động sinh slug SEO.
- **Quản lý Đơn hàng (Orders):** Xem chi tiết đơn hàng, cập nhật trạng thái đơn (Chờ xử lý, Đang giao, Hoàn thành, Đã hủy) và trạng thái thanh toán.
- **Quản lý Khách hàng (Customers):** Xem danh sách, lịch sử mua hàng, khóa/mở khóa tài khoản.
- **Quản lý Mã Giảm Giá (Coupons):** Thiết lập mã giảm theo % hoặc số tiền cố định, giới hạn lượt dùng và ngày hết hạn.
- **Quản lý Banner (Banners):** Thay đổi ảnh trình chiếu trang chủ, tiêu đề, nút bấm.
- **Quản lý Bài viết & Tin tức (Blog / Journal):** Quản lý bài viết thời trang, mẹo phối đồ.
- **Quản lý Đánh giá (Reviews):** Kiểm duyệt, duyệt hoặc ẩn đánh giá của khách hàng.
- **Cài đặt Hệ thống (Settings):** Tùy chỉnh hotline, email, địa chỉ showroom, chính sách freeship.

---

## 🛠️ Công Nghệ Sử Dụng (Tech Stack)

- **Backend:** PHP 8.2+, Laravel 12.x, Laravel Breeze, Eloquent ORM.
- **Database:** MySQL 8.0+ / MariaDB.
- **Frontend:** Laravel Blade, Tailwind CSS v4, Alpine.js, Vite.
- **Testing:** PHPUnit / Pest PHP (43 tests, 159 assertions passed).

---

## 🚀 Hướng Dẫn Cài Đặt & Chạy Cho Người Khác (Getting Started)

Nếu bạn tải mã nguồn này từ GitHub về máy mới, chỉ cần làm theo các bước sau:

### Yêu Cầu Môi Trường
- **PHP >= 8.2** (đã bật các extension: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`)
- **Composer** (bản mới nhất)
- **Node.js >= 18** & **npm**
- **MySQL Server** (XAMPP, Laragon, WampServer hoặc Docker)

---

### Các Bước Thực Hiện Chi Tiết

#### Bước 1: Di chuyển vào thư mục dự án
```bash
cd thabero-clone
```

#### Bước 2: Cài đặt các thư viện PHP (Composer)
```bash
composer install
```

#### Bước 3: Cài đặt các thư viện Frontend (NPM)
```bash
npm install
```

#### Bước 4: Thiết lập file môi trường (.env)
Sao chép file `.env.example` thành `.env`:
- Trên Windows (PowerShell / CMD):
  ```bash
  copy .env.example .env
  ```
- Trên Linux / macOS:
  ```bash
  cp .env.example .env
  ```

#### Bước 5: Tạo mã khóa ứng dụng (Application Key)
```bash
php artisan key:generate
```

#### Bước 6: Khởi tạo Cơ Sở Dữ Liệu (Database)
1. Mở phpMyAdmin hoặc MySQL Client, tạo một Database mới có tên:
   ```sql
   CREATE DATABASE thabero_clone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Kiểm tra thông tin kết nối trong file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=thabero_clone
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Nạp dữ liệu vào Database (Chọn 1 trong 2 cách):**
   - **Cách 1 (Khuyên dùng - Chuẩn Laravel):** Chạy lệnh Migrate & Seed để tự động tạo cấu trúc và nạp 50 sản phẩm mẫu, 21 tài khoản, đơn hàng, banner, bài viết:
     ```bash
     php artisan migrate --seed
     ```
   - **Cách 2 (Import file SQL có sẵn):** Nhập trực tiếp file SQL đã được xuất sẵn tại thư mục:
     ```
     database/novawear_database.sql
     ```
     *(Vào phpMyAdmin -> chọn database `thabero_clone` -> bấm thẻ **Import** -> chọn file `database/novawear_database.sql` -> bấm **Import**)*.

#### Bước 7: Build giao diện Frontend
```bash
npm run build
```

#### Bước 8: Khởi chạy Server
```bash
php artisan serve
```
Truy cập website tại: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Tài Khoản Đăng Nhập Mẫu (Demo Accounts)

| Vai trò | Email đăng nhập | Mật khẩu mặc định | Đường dẫn |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin@example.com` | `password` | `http://127.0.0.1:8000/admin` |
| **Khách hàng mẫu (Customer)** | `customer@example.com` | `password` | `http://127.0.0.1:8000/login` |

---

## 🎟️ Mã Khuyến Mãi Có Sẵn Để Thử Nghiệm

- **`WELCOME10`**: Giảm 10% cho đơn hàng đầu tiên.
- **`NOVA50K`**: Giảm trực tiếp 50.000₫ cho đơn từ 400.000₫.
- **`VIP20`**: Giảm 20% đặc quyền thành viên VIP.

---

## 🧪 Chạy Kiểm Thử Tự Động (Automated Tests)

Dự án đã được tích hợp bộ kiểm thử tự động 100% cho mọi luồng CRUD Admin, Giỏ hàng, Đặt hàng và Quyền truy cập:
```bash
php artisan test
```
*Kết quả: 43 test suites / 159 assertions đạt 100% PASS.*

---

## 📄 Bản Quyền
Dự án được xây dựng và phát triển độc lập cho thương hiệu **NOVAWEAR Contemporary Fashion**.
Mọi hình ảnh mẫu sử dụng nguồn mở Unsplash phục vụ mục đích trình diễn thiết kế.
