<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\AttributeValue;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ----------------------------------------------------
        // 1. USERS & ADMIN
        // ----------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin NOVAWEAR',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0901234567',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $demoCustomer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Khánh Nguyễn',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '0987654321',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        Address::create([
            'user_id' => $demoCustomer->id,
            'full_name' => 'Khánh Nguyễn',
            'phone' => '0987654321',
            'address' => '123 Đường Lê Lợi, Phường Bến Nghé',
            'province' => 'TP. Hồ Chí Minh',
            'district' => 'Quận 1',
            'ward' => 'Phường Bến Nghé',
            'is_default' => true,
        ]);

        $customers = User::factory(19)->create();
        $allCustomers = collect([$demoCustomer])->merge($customers);

        // ----------------------------------------------------
        // 2. BRANDS (5 Brands)
        // ----------------------------------------------------
        $brandsData = [
            ['name' => 'NOVA Studio', 'slug' => 'nova-studio', 'description' => 'Dòng thời trang may đo và thiết kế tối giản đương đại.'],
            ['name' => 'Urban Minimal', 'slug' => 'urban-minimal', 'description' => 'Phong cách đường phố hiện đại, tối giản và phóng khoáng.'],
            ['name' => 'Aesthetic Lab', 'slug' => 'aesthetic-lab', 'description' => 'Nghiên cứu phom dáng và chất liệu vải sợi tự nhiên cao cấp.'],
            ['name' => 'Atelier Noir', 'slug' => 'atelier-noir', 'description' => 'Thời trang đơn sắc sang trọng, thanh lịch cho mọi dịp.'],
            ['name' => 'Linen & Silk', 'slug' => 'linen-silk', 'description' => 'Chuyên các sản phẩm từ sợi đũi tự nhiên, thoáng mát và bền bỉ.'],
        ];

        $brands = collect();
        foreach ($brandsData as $b) {
            $brands->push(Brand::create([
                'name' => $b['name'],
                'slug' => $b['slug'],
                'description' => $b['description'],
                'is_featured' => true,
                'status' => 'active',
            ]));
        }

        // ----------------------------------------------------
        // 3. CATEGORIES (8 Categories)
        // ----------------------------------------------------
        $categoriesData = [
            [
                'name' => 'Áo Sơ Mi',
                'slug' => 'ao-so-mi',
                'image' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=600&q=80',
                'description' => 'Áo sơ mi oxford, lụa, cổ tàu thanh lịch công sở và dạo phố.',
            ],
            [
                'name' => 'Áo Thun Oversize',
                'slug' => 'ao-thun',
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80',
                'description' => 'Áo thun cotton 100% 250gsm dày dặn, thấm hút mồ hôi tốt.',
            ],
            [
                'name' => 'Áo Polo Minimalist',
                'slug' => 'ao-polo',
                'image' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?auto=format&fit=crop&w=600&q=80',
                'description' => 'Áo polo dệt kim, cá sấu cao cấp phom dáng gọn gàng.',
            ],
            [
                'name' => 'Quần Jean & Denim',
                'slug' => 'quan-jean',
                'image' => 'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=600&q=80',
                'description' => 'Quần bò denim wash cao cấp, ống suông, ống đứng tôn dáng.',
            ],
            [
                'name' => 'Quần Tây & Trousers',
                'slug' => 'quan-tay',
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=600&q=80',
                'description' => 'Quần tây xếp ly đứng dáng, co giãn nhẹ thoải mái vận động.',
            ],
            [
                'name' => 'Áo Khoác & Blazer',
                'slug' => 'ao-khoac',
                'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=600&q=80',
                'description' => 'Blazer một hàng khuy, áo khoác bomber và jacket tối giản.',
            ],
            [
                'name' => 'Váy & Đầm Thiết Kế',
                'slug' => 'vay-dam',
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=600&q=80',
                'description' => 'Váy slip dress, đầm suông, chân váy chữ A phong cách Pháp.',
            ],
            [
                'name' => 'Phụ Kiện Thời Trang',
                'slug' => 'phu-kien',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80',
                'description' => 'Mũ len, túi tote vải canvas, thắt lưng da bò thật, kính mát.',
            ],
        ];

        $categories = collect();
        foreach ($categoriesData as $idx => $c) {
            $categories->push(Category::create([
                'name' => $c['name'],
                'slug' => $c['slug'],
                'image' => $c['image'],
                'description' => $c['description'],
                'is_featured' => true,
                'status' => 'active',
                'sort_order' => $idx + 1,
                'meta_title' => $c['name'] . ' Cao Cấp | NOVAWEAR',
                'meta_description' => $c['description'],
            ]));
        }

        // ----------------------------------------------------
        // 4. ATTRIBUTES & VALUES
        // ----------------------------------------------------
        $sizeAttr = ProductAttribute::create(['name' => 'Kích cỡ', 'code' => 'size']);
        $sizes = ['S', 'M', 'L', 'XL'];
        foreach ($sizes as $s) {
            AttributeValue::create(['attribute_id' => $sizeAttr->id, 'value' => $s]);
        }

        $colorAttr = ProductAttribute::create(['name' => 'Màu sắc', 'code' => 'color']);
        $colors = [
            ['value' => 'Đen', 'color_code' => '#111111'],
            ['value' => 'Trắng', 'color_code' => '#FFFFFF'],
            ['value' => 'Xám', 'color_code' => '#6B7280'],
            ['value' => 'Be', 'color_code' => '#D4B996'],
            ['value' => 'Xanh Navy', 'color_code' => '#1E293B'],
        ];
        foreach ($colors as $c) {
            AttributeValue::create([
                'attribute_id' => $colorAttr->id,
                'value' => $c['value'],
                'color_code' => $c['color_code'],
            ]);
        }

        // ----------------------------------------------------
        // 5. PRODUCTS (50 Products with Real Data & Images)
        // ----------------------------------------------------
        $fashionImages = [
            'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1581655353564-df123a1eb820?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1485968579580-b6d095142e6e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1550614000-4895a10e1bfd?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=800&q=80',
        ];

        $productTemplates = [
            // Áo sơ mi
            ['name' => 'Áo Sơ Mi Oxford Dài Tay Relaxed', 'cat_idx' => 0, 'price' => 450000, 'sale' => 390000],
            ['name' => 'Áo Sơ Mi Lụa Cổ Cuba Minimal', 'cat_idx' => 0, 'price' => 520000, 'sale' => 450000],
            ['name' => 'Áo Sơ Mi Đũi Cổ Trụ Nhật Bản', 'cat_idx' => 0, 'price' => 480000, 'sale' => null],
            ['name' => 'Áo Sơ Mi Kẻ Sọc Classic Fit', 'cat_idx' => 0, 'price' => 420000, 'sale' => 350000],
            ['name' => 'Áo Sơ Mi Trắng Basic Dáng Suông', 'cat_idx' => 0, 'price' => 390000, 'sale' => null],
            ['name' => 'Áo Sơ Mi Linen Thô Tự Nhiên', 'cat_idx' => 0, 'price' => 550000, 'sale' => 490000],

            // Áo thun
            ['name' => 'Áo Thun Trơn Heavyweight 260GSM', 'cat_idx' => 1, 'price' => 290000, 'sale' => 230000],
            ['name' => 'Áo Thun Oversize Form Hộp Studio', 'cat_idx' => 1, 'price' => 320000, 'sale' => null],
            ['name' => 'Áo Thun Typography Monogram Minimal', 'cat_idx' => 1, 'price' => 350000, 'sale' => 290000],
            ['name' => 'Áo Thun Cotton Supima Mềm Mịn', 'cat_idx' => 1, 'price' => 380000, 'sale' => null],
            ['name' => 'Áo Thun Washed Vintage Grey', 'cat_idx' => 1, 'price' => 340000, 'sale' => 280000],
            ['name' => 'Áo Thun Raglan Phối Màu Retro', 'cat_idx' => 1, 'price' => 310000, 'sale' => null],

            // Polo
            ['name' => 'Áo Polo Dệt Kim Cổ Bẻ Cao Cấp', 'cat_idx' => 2, 'price' => 490000, 'sale' => 420000],
            ['name' => 'Áo Polo Pique Cotton Thoáng Khí', 'cat_idx' => 2, 'price' => 420000, 'sale' => null],
            ['name' => 'Áo Polo Không Cúc Cổ Chữ V', 'cat_idx' => 2, 'price' => 460000, 'sale' => 380000],
            ['name' => 'Áo Polo Thể Thao Phối Viền Tinh Tế', 'cat_idx' => 2, 'price' => 390000, 'sale' => null],
            ['name' => 'Áo Polo Dài Tay Dệt Sợi Cotton', 'cat_idx' => 2, 'price' => 520000, 'sale' => 460000],
            ['name' => 'Áo Polo Mercerized Bóng Nhẹ Sang Trọng', 'cat_idx' => 2, 'price' => 580000, 'sale' => null],

            // Quần jean
            ['name' => 'Quần Jean Ống Suông Straight Denim', 'cat_idx' => 3, 'price' => 590000, 'sale' => 490000],
            ['name' => 'Quần Jean Tapered Wash Xanh Sáng', 'cat_idx' => 3, 'price' => 620000, 'sale' => null],
            ['name' => 'Quần Jean Đen Raw Denim Không Giãn', 'cat_idx' => 3, 'price' => 680000, 'sale' => 590000],
            ['name' => 'Quần Jean Rách Gối Nhẹ Streetwear', 'cat_idx' => 3, 'price' => 580000, 'sale' => null],
            ['name' => 'Quần Jean Cạp Cao Ống Rộng Nữ', 'cat_idx' => 3, 'price' => 560000, 'sale' => 480000],
            ['name' => 'Quần Jean Vintage Wash Màu Xám Khói', 'cat_idx' => 3, 'price' => 640000, 'sale' => 550000],

            // Quần tây
            ['name' => 'Quần Tây 2 Ly Dáng Suông Hàn Quốc', 'cat_idx' => 4, 'price' => 550000, 'sale' => 470000],
            ['name' => 'Quần Chinos Slim-Fit Co Giãn Nhẹ', 'cat_idx' => 4, 'price' => 480000, 'sale' => null],
            ['name' => 'Quần Tây Ống Rộng Lưng Chun Ẩn', 'cat_idx' => 4, 'price' => 520000, 'sale' => 450000],
            ['name' => 'Quần Kaki Ống Đứng Cargo Minimal', 'cat_idx' => 4, 'price' => 510000, 'sale' => null],
            ['name' => 'Quần Tây Vải Wool Blend Cao Cấp', 'cat_idx' => 4, 'price' => 750000, 'sale' => 650000],
            ['name' => 'Quần Short Kaki Đi Dạo Thoải Mái', 'cat_idx' => 4, 'price' => 320000, 'sale' => null],

            // Áo khoác & Blazer
            ['name' => 'Áo Blazer Relaxed Fit Đơn Sắc', 'cat_idx' => 5, 'price' => 950000, 'sale' => 820000],
            ['name' => 'Áo Khoác Bomber Kaki Dáng Hộp', 'cat_idx' => 5, 'price' => 790000, 'sale' => null],
            ['name' => 'Áo Khoác Harrington Lót Kẻ Caro', 'cat_idx' => 5, 'price' => 850000, 'sale' => 720000],
            ['name' => 'Áo Khoác Gió Kháng Nước Minimal', 'cat_idx' => 5, 'price' => 620000, 'sale' => null],
            ['name' => 'Áo Măng Tô Dáng Dài Thanh Lịch', 'cat_idx' => 5, 'price' => 1450000, 'sale' => 1250000],
            ['name' => 'Áo Cardigan Dệt Kim Họa Tiết Gân', 'cat_idx' => 5, 'price' => 590000, 'sale' => 490000],

            // Váy & Đầm
            ['name' => 'Váy Slip Dress Lụa Satin Tối Giản', 'cat_idx' => 6, 'price' => 650000, 'sale' => 550000],
            ['name' => 'Đầm Suông Cổ Chữ V Phong Cách Pháp', 'cat_idx' => 6, 'price' => 720000, 'sale' => null],
            ['name' => 'Chân Váy Xếp Ly Dài Qua Gối', 'cat_idx' => 6, 'price' => 460000, 'sale' => 390000],
            ['name' => 'Đầm Sơ Mi Thắt Đai Nữ Tính', 'cat_idx' => 6, 'price' => 680000, 'sale' => null],
            ['name' => 'Chân Váy Chữ A Denim Xẻ Tà Trước', 'cat_idx' => 6, 'price' => 490000, 'sale' => 420000],
            ['name' => 'Váy Hai Dây Dáng Xòe Mùa Hè', 'cat_idx' => 6, 'price' => 580000, 'sale' => null],

            // Phụ kiện
            ['name' => 'Túi Tote Canvas Dày Phối Da Thật', 'cat_idx' => 7, 'price' => 280000, 'sale' => 220000],
            ['name' => 'Thắt Lưng Da Bò Ý Khóa Bạc Mờ', 'cat_idx' => 7, 'price' => 350000, 'sale' => null],
            ['name' => 'Mũ Lưỡi Trai Cotton Thêu Logo Tối Giản', 'cat_idx' => 7, 'price' => 190000, 'sale' => 150000],
            ['name' => 'Kính Mát Gọng Acetate Chống UV400', 'cat_idx' => 7, 'price' => 420000, 'sale' => null],
            ['name' => 'Khăn Choàng Cổ Wool Cashmere Mềm', 'cat_idx' => 7, 'price' => 490000, 'sale' => 390000],
            ['name' => 'Túi Đeo Chéo Crossbody Chống Thấm', 'cat_idx' => 7, 'price' => 340000, 'sale' => null],
            ['name' => 'Vớ Cotton Kháng Khuẩn Set 3 Đôi', 'cat_idx' => 7, 'price' => 120000, 'sale' => 99000],
            ['name' => 'Ví Ngắn Đựng Thẻ Da Epsom Thủ Công', 'cat_idx' => 7, 'price' => 390000, 'sale' => 320000],
        ];

        $createdProducts = collect();

        foreach ($productTemplates as $index => $item) {
            $cat = $categories[$item['cat_idx']];
            $brand = $brands[$index % $brands->count()];
            $slug = Str::slug($item['name']) . '-' . ($index + 1);

            $product = Product::create([
                'category_id' => $cat->id,
                'brand_id' => $brand->id,
                'name' => $item['name'],
                'slug' => $slug,
                'sku' => 'NVW-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'description' => "Thiết kế hiện đại mang triết lý tối giản từ NOVAWEAR. Sử dụng chất liệu vải tuyển chọn kỹ càng, đem lại sự êm ái, thoáng mát và độ đứng phom hoàn hảo suốt ngày dài. Sản phẩm dễ dàng phối cùng nhiều phong cách trang phục từ đi làm, đi cà phê cho đến các buổi tiệc nhẹ nhàng.",
                'short_description' => "Chất vải tuyển chọn cao cấp, thoáng mát, phom dáng chuẩn chỉ tôn đường nét người mặc.",
                'price' => $item['price'],
                'sale_price' => $item['sale'],
                'stock' => rand(15, 120),
                'status' => 'active',
                'featured' => ($index % 3 === 0),
                'bestseller' => ($index % 4 === 0),
                'new_arrival' => ($index % 2 === 0),
                'meta_title' => $item['name'] . ' - NOVAWEAR',
                'meta_description' => "Mua ngay " . $item['name'] . " chất lượng cao cấp, giao hàng tận nơi nhanh chóng toàn quốc.",
            ]);

            $createdProducts->push($product);

            // Add 3 images per product
            $imgBaseIdx = ($index * 2) % count($fashionImages);
            $img1 = $fashionImages[$imgBaseIdx];
            $img2 = $fashionImages[($imgBaseIdx + 1) % count($fashionImages)];
            $img3 = $fashionImages[($imgBaseIdx + 2) % count($fashionImages)];

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $img1,
                'sort_order' => 1,
                'is_primary' => true,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $img2,
                'sort_order' => 2,
                'is_primary' => false,
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $img3,
                'sort_order' => 3,
                'is_primary' => false,
            ]);

            // Add Variants (Sizes S, M, L, XL with colors)
            $sampleColors = [
                'Đen' => 'BLK',
                'Trắng' => 'WHT',
                'Xám' => 'GRY',
            ];
            $sampleSizes = ['S', 'M', 'L', 'XL'];
            foreach ($sampleColors as $colorName => $colorCode) {
                foreach ($sampleSizes as $s) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $s,
                        'color' => $colorName,
                        'sku' => $product->sku . '-' . $s . '-' . $colorCode,
                        'price' => $product->price,
                        'stock' => rand(5, 30),
                    ]);
                }
            }
        }

        // ----------------------------------------------------
        // 6. BANNERS (Hero & Promotional)
        // ----------------------------------------------------
        Banner::create([
            'title' => 'NOVAWEAR ESSENTIALS 2026',
            'subtitle' => 'Nâng tầm phong cách cá nhân với thiết kế tối giản, tinh tế từng chi tiết.',
            'image' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1920&q=85',
            'link' => '/shop',
            'button_text' => 'Khám Phá Ngay',
            'position' => 'hero',
            'sort_order' => 1,
            'status' => 'active',
        ]);

        Banner::create([
            'title' => 'THE NEW CONTEMPORARY',
            'subtitle' => 'Bộ sưu tập Thu Đông mới nhất với chất liệu sợi tự nhiên cao cấp.',
            'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=1920&q=85',
            'link' => '/shop?category=ao-khoac',
            'button_text' => 'Xem Bộ Sưu Tập',
            'position' => 'hero',
            'sort_order' => 2,
            'status' => 'active',
        ]);

        Banner::create([
            'title' => 'ƯU ĐÃI ĐỘC QUYỀN MÙA NÀY',
            'subtitle' => 'Nhập mã WELCOME10 để nhận ngay ưu đãi 10% cho đơn hàng đầu tiên.',
            'image' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=1600&q=80',
            'link' => '/shop',
            'button_text' => 'Mua Sắm Ngay',
            'position' => 'promo',
            'sort_order' => 1,
            'status' => 'active',
        ]);

        // ----------------------------------------------------
        // 7. COUPONS
        // ----------------------------------------------------
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 200000,
            'max_discount_amount' => 100000,
            'start_date' => now()->subDays(5),
            'end_date' => now()->addMonths(6),
            'usage_limit' => 500,
            'used_count' => 12,
            'status' => 'active',
        ]);

        Coupon::create([
            'code' => 'NOVA50K',
            'type' => 'fixed',
            'value' => 50000,
            'min_order_amount' => 500000,
            'max_discount_amount' => null,
            'start_date' => now()->subDays(1),
            'end_date' => now()->addMonths(3),
            'usage_limit' => 200,
            'used_count' => 8,
            'status' => 'active',
        ]);

        Coupon::create([
            'code' => 'VIP20',
            'type' => 'percentage',
            'value' => 20,
            'min_order_amount' => 1000000,
            'max_discount_amount' => 300000,
            'start_date' => now()->subDays(2),
            'end_date' => now()->addMonths(2),
            'usage_limit' => 50,
            'used_count' => 4,
            'status' => 'active',
        ]);

        // ----------------------------------------------------
        // 8. BLOG (10 Articles with Categories)
        // ----------------------------------------------------
        $blogCat1 = BlogCategory::create(['name' => 'Xu Hướng Thời Trang', 'slug' => 'xu-huong-thoi-trang']);
        $blogCat2 = BlogCategory::create(['name' => 'Phong Cách Sống', 'slug' => 'phong-cach-song']);
        $blogCat3 = BlogCategory::create(['name' => 'Cẩm Nang Phối Đồ', 'slug' => 'cam-nang-phoi-do']);

        $blogsData = [
            [
                'title' => 'Xu hướng Capsule Wardrobe: Tối giản tủ đồ để cuộc sống thêm tự do',
                'cat' => $blogCat2->id,
                'img' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Cách xây dựng một tủ đồ chỉ với 30 món nhưng có thể phối được hơn 100 bộ trang phục thanh lịch mỗi ngày.',
            ],
            [
                'title' => 'Bí quyết chọn áo sơ mi trắng hoàn hảo cho mọi phom người',
                'cat' => $blogCat3->id,
                'img' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Từ cổ áo áo, chất liệu vải oxford hay poplin, đến độ ôm của thân áo đều quyết định sự tự tin của bạn.',
            ],
            [
                'title' => 'Chất liệu Linen tự nhiên: Vì sao vải đũi là chân ái của mùa hè?',
                'cat' => $blogCat1->id,
                'img' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Tìm hiểu nguồn gốc và đặc tính thoáng mát vượt trội của sợi linen hữu cơ được dệt thủ công.',
            ],
            [
                'title' => 'Nghệ thuật phối màu trung tính: Đen, Trắng, Be và Xám chưa bao giờ lỗi mốt',
                'cat' => $blogCat3->id,
                'img' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Công thức kết hợp các dải màu earth-tone giúp tổng thể trang phục luôn toát lên vẻ sang trọng.',
            ],
            [
                'title' => 'Cách chăm sóc và giặt quần jean denim để giữ phom dáng nguyên bản',
                'cat' => $blogCat3->id,
                'img' => 'https://images.unsplash.com/photo-1542272604-780c96856592?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Những mẹo đơn giản nhưng cực kỳ hiệu quả giúp chiếc quần bò yêu thích của bạn luôn như mới.',
            ],
            [
                'title' => 'Blazer hiện đại: Từ trang phục công sở đến điểm nhấn dạo phố cuối tuần',
                'cat' => $blogCat1->id,
                'img' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Khám phá 5 cách phối áo blazer dáng relaxed cùng quần jeans, áo thun hay slip dress.',
            ],
            [
                'title' => 'Thời trang bền vững: Lựa chọn chất liệu thân thiện với môi trường',
                'cat' => $blogCat2->id,
                'img' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'NOVAWEAR cam kết sử dụng bao bì tái chế và quy trình nhuộm an toàn bảo vệ thiên nhiên.',
            ],
            [
                'title' => 'Top 5 mẫu áo thun cotton 100% không thể thiếu trong tủ đồ chàng trai',
                'cat' => $blogCat3->id,
                'img' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Trải nghiệm độ dày dặn 250gsm và kỹ thuật dệt chống nhão cổ áo sau nhiều lần giặt.',
            ],
            [
                'title' => 'Phong cách Monochromatic: Đơn giản nhưng tạo ấn tượng thị giác sâu sắc',
                'cat' => $blogCat1->id,
                'img' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Diện cả cây đen hay cả cây be mà không bị nhàm chán bằng cách chơi đùa với chất liệu vải.',
            ],
            [
                'title' => 'Phụ kiện tối giản: Chi tiết nhỏ tạo nên sự khác biệt lớn cho diện mạo',
                'cat' => $blogCat2->id,
                'img' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80',
                'excerpt' => 'Từ chiếc thắt lưng da bò đến túi tote canvas, hãy để phụ kiện kể câu chuyện phong cách của bạn.',
            ],
        ];

        foreach ($blogsData as $b) {
            $slug = Str::slug($b['title']);
            Blog::create([
                'category_id' => $b['cat'],
                'user_id' => $admin->id,
                'title' => $b['title'],
                'slug' => $slug,
                'excerpt' => $b['excerpt'],
                'content' => "<p>{$b['excerpt']}</p><p>Phong cách thời trang tối giản không chỉ đơn thuần là việc cắt giảm chi tiết, mà là sự chắt lọc tinh túy nhất về phom dáng, chất liệu và cảm giác thoải mái của người mặc. Tại NOVAWEAR, chúng tôi tin rằng cái đẹp thực sự đến từ sự tự nhiên và vừa vặn hoàn hảo.</p><p>Khi bạn khoác lên mình một chiếc áo vừa vặn được may từ chất liệu sợi cotton cao cấp, bạn sẽ cảm nhận được sự tự tin từ bên trong. Đó chính là tinh thần mà bộ sưu tập đương đại của chúng tôi muốn truyền tải.</p>",
                'image' => $b['img'],
                'views' => rand(120, 1500),
                'status' => 'published',
                'meta_title' => $b['title'] . ' | NOVAWEAR',
                'meta_description' => $b['excerpt'],
            ]);
        }

        // ----------------------------------------------------
        // 9. SAMPLE REVIEWS (30 Reviews)
        // ----------------------------------------------------
        $reviewTexts = [
            'Chất lượng vải cực kỳ tốt, form đứng dáng y như hình mẫu. Đóng gói rất chỉn chu!',
            'Áo mặc rất mát và mềm mịn, đường kim mũi chỉ tỉ mỉ. Rất xứng đáng với giá tiền.',
            'Giao hàng nhanh, đóng hộp đẹp mắt. Shop tư vấn size rất chuẩn.',
            'Màu sắc bên ngoài sang và nhã nhặn hơn trên ảnh. Rất hài lòng!',
            'Form dáng rất tôn dáng, giặt máy không bị xù lông hay mất phom. Sẽ ủng hộ tiếp.',
            'Thiết kế tối giản đẹp đúng gu mình tìm bấy lâu nay. 10 sao cho shop!',
        ];

        foreach ($createdProducts->take(15) as $prod) {
            for ($r = 0; $r < 2; $r++) {
                $reviewer = $allCustomers->random();
                Review::create([
                    'product_id' => $prod->id,
                    'user_id' => $reviewer->id,
                    'rating' => rand(4, 5),
                    'comment' => $reviewTexts[array_rand($reviewTexts)],
                    'status' => 'approved',
                ]);
            }
        }

        // ----------------------------------------------------
        // 10. SAMPLE ORDERS (12 Orders)
        // ----------------------------------------------------
        $orderStatuses = ['pending', 'confirmed', 'processing', 'shipping', 'completed', 'completed', 'cancelled'];
        foreach (range(1, 12) as $oNum) {
            $user = $allCustomers->random();
            $status = $orderStatuses[array_rand($orderStatuses)];
            $payMethod = $oNum % 2 === 0 ? 'cod' : 'bank_transfer';
            $payStatus = in_array($status, ['completed', 'shipping', 'processing']) ? 'paid' : 'pending';

            $orderProds = $createdProducts->random(rand(1, 3));
            $subtotal = 0;
            $itemsData = [];

            foreach ($orderProds as $p) {
                $qty = rand(1, 2);
                $pPrice = $p->effective_price;
                $lineTotal = $pPrice * $qty;
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id' => $p->id,
                    'product_variant_id' => $p->variants->first()?->id,
                    'product_name' => $p->name,
                    'product_sku' => $p->sku,
                    'product_image' => $p->primary_image_url,
                    'price' => $pPrice,
                    'quantity' => $qty,
                    'total' => $lineTotal,
                    'options' => ['size' => 'L', 'color' => 'Đen'],
                ];
            }

            $shipping = 30000;
            $discount = 0;
            $total = $subtotal + $shipping - $discount;

            $order = Order::create([
                'order_number' => 'NVW' . date('ymd') . str_pad($oNum, 4, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_phone' => $user->phone ?? '0912345678',
                'customer_email' => $user->email,
                'shipping_address' => 'Số ' . rand(10, 200) . ' Đường Nguyễn Huệ, Phường Bến Nghé',
                'province' => 'TP. Hồ Chí Minh',
                'district' => 'Quận 1',
                'ward' => 'Phường Bến Nghé',
                'note' => $oNum % 3 === 0 ? 'Giao giờ hành chính giúp em ạ.' : null,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'shipping_fee' => $shipping,
                'total' => $total,
                'payment_method' => $payMethod,
                'payment_status' => $payStatus,
                'status' => $status,
                'created_at' => now()->subDays(rand(0, 30)),
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }
        }

        // ----------------------------------------------------
        // 11. GENERAL SETTINGS
        // ----------------------------------------------------
        Setting::set('site_name', 'NOVAWEAR');
        Setting::set('site_tagline', 'Contemporary Minimalist Fashion');
        Setting::set('site_email', 'contact@novawear.vn');
        Setting::set('site_phone', '1900 888 999');
        Setting::set('site_address', 'Tầng 5, Tòa nhà NOVA Tower, 68 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh');
        Setting::set('shipping_fee', '30000');
        Setting::set('free_shipping_min', '500000');
        Setting::set('facebook', 'https://facebook.com/novawear');
        Setting::set('instagram', 'https://instagram.com/novawear');
    }
}
