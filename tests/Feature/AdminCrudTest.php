<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Category $category;
    private Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->category = Category::create([
            'name' => 'Áo Khoác Nam',
            'slug' => 'ao-khoac-nam',
            'status' => 'active',
        ]);

        $this->brand = Brand::create([
            'name' => 'NOVA Premium',
            'slug' => 'nova-premium',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_crud_product(): void
    {
        // 1. Create Product
        $createResponse = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Áo Khoác Bomber Minimal',
            'sku' => 'TEST-JKT-01',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'price' => 750000,
            'sale_price' => 650000,
            'stock' => 25,
            'status' => 'active',
            'short_description' => 'Áo khoác bomber cao cấp',
            'description' => 'Mô tả chi tiết bomber cao cấp',
            'image_urls' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=800&q=80',
        ]);
        $createResponse->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'sku' => 'TEST-JKT-01',
            'price' => 750000,
        ]);

        $product = Product::where('sku', 'TEST-JKT-01')->first();
        $this->assertNotNull($product);

        // 2. Read in Admin list
        $listResponse = $this->actingAs($this->admin)->get(route('admin.products.index'));
        $listResponse->assertStatus(200);
        $listResponse->assertSee('Áo Khoác Bomber Minimal');

        // 3. Update Product
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
            'name' => 'Áo Khoác Bomber Minimal Đã Sửa',
            'sku' => 'TEST-JKT-01',
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'price' => 790000,
            'sale_price' => 690000,
            'stock' => 30,
            'status' => 'active',
        ]);
        $updateResponse->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Áo Khoác Bomber Minimal Đã Sửa',
            'price' => 790000,
        ]);

        // 4. Delete Product
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product));
        $deleteResponse->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    public function test_admin_can_crud_category(): void
    {
        // 1. Create Category
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Quần Tây Âu',
            'description' => 'Danh mục quần tây',
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Quần Tây Âu']);

        $cat = Category::where('name', 'Quần Tây Âu')->first();

        // 2. Update Category
        $update = $this->actingAs($this->admin)->put(route('admin.categories.update', $cat), [
            'name' => 'Quần Tây Âu Nam',
            'status' => 'active',
        ]);
        $update->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Quần Tây Âu Nam']);

        // 3. Delete Category
        $delete = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $cat));
        $delete->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }

    public function test_admin_can_crud_brand(): void
    {
        // 1. Create Brand
        $response = $this->actingAs($this->admin)->post(route('admin.brands.store'), [
            'name' => 'NOVA Athletics',
            'description' => 'Dòng thể thao',
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.brands.index'));
        $this->assertDatabaseHas('brands', ['name' => 'NOVA Athletics']);

        $brand = Brand::where('name', 'NOVA Athletics')->first();

        // 2. Update Brand
        $update = $this->actingAs($this->admin)->put(route('admin.brands.update', $brand), [
            'name' => 'NOVA Activewear',
            'status' => 'active',
        ]);
        $update->assertRedirect(route('admin.brands.index'));
        $this->assertDatabaseHas('brands', ['name' => 'NOVA Activewear']);

        // 3. Delete Brand
        $delete = $this->actingAs($this->admin)->delete(route('admin.brands.destroy', $brand));
        $delete->assertRedirect(route('admin.brands.index'));
        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    public function test_admin_can_manage_orders(): void
    {
        $order = Order::create([
            'order_number' => 'NVW999999',
            'customer_name' => 'Test Customer',
            'customer_phone' => '0912345678',
            'customer_email' => 'customer@test.com',
            'shipping_address' => '456 Le Duan',
            'province' => 'TP. Ho Chi Minh',
            'district' => 'Quan 1',
            'ward' => 'Ben Nghe',
            'subtotal' => 500000,
            'total' => 530000,
            'shipping_fee' => 30000,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        // View Order
        $view = $this->actingAs($this->admin)->get(route('admin.orders.show', $order));
        $view->assertStatus(200);
        $view->assertSee('NVW999999');

        // Update Order Status
        $updateStatus = $this->actingAs($this->admin)->patch(route('admin.orders.status', $order), [
            'status' => 'shipping',
            'payment_status' => 'paid',
        ]);
        $updateStatus->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipping',
            'payment_status' => 'paid',
        ]);
    }

    public function test_admin_can_manage_customers(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'status' => 'active',
        ]);

        // View Customer Detail
        $view = $this->actingAs($this->admin)->get(route('admin.customers.show', $customer));
        $view->assertStatus(200);

        // Toggle Status
        $toggle = $this->actingAs($this->admin)->patch(route('admin.customers.toggle-status', $customer));
        $toggle->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'status' => 'inactive',
        ]);
    }

    public function test_admin_can_crud_coupons(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.coupons.store'), [
            'code' => 'DISCOUNT30',
            'type' => 'percentage',
            'value' => 30,
            'min_order_amount' => 300000,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', ['code' => 'DISCOUNT30', 'value' => 30]);

        $coupon = Coupon::where('code', 'DISCOUNT30')->first();

        // 2. Update
        $update = $this->actingAs($this->admin)->put(route('admin.coupons.update', $coupon), [
            'code' => 'DISCOUNT30',
            'type' => 'percentage',
            'value' => 35,
            'status' => 'active',
        ]);
        $update->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseHas('coupons', ['code' => 'DISCOUNT30', 'value' => 35]);

        // 3. Delete
        $delete = $this->actingAs($this->admin)->delete(route('admin.coupons.destroy', $coupon));
        $delete->assertRedirect(route('admin.coupons.index'));
        $this->assertDatabaseMissing('coupons', ['id' => $coupon->id]);
    }

    public function test_admin_can_crud_banners(): void
    {
        // 1. Create Banner
        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), [
            'title' => 'Flash Sale Cuối Tuần',
            'position' => 'hero',
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseHas('banners', ['title' => 'Flash Sale Cuối Tuần']);

        $banner = Banner::where('title', 'Flash Sale Cuối Tuần')->first();

        // 2. Update Banner
        $update = $this->actingAs($this->admin)->put(route('admin.banners.update', $banner), [
            'title' => 'Flash Sale Siêu Khủng',
            'position' => 'promo',
            'status' => 'active',
        ]);
        $update->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseHas('banners', ['title' => 'Flash Sale Siêu Khủng', 'position' => 'promo']);

        // 3. Delete Banner
        $delete = $this->actingAs($this->admin)->delete(route('admin.banners.destroy', $banner));
        $delete->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseMissing('banners', ['id' => $banner->id]);
    }

    public function test_admin_can_crud_blog(): void
    {
        $bCat = BlogCategory::create(['name' => 'Thời Trang', 'slug' => 'thoi-trang']);

        // 1. Create Blog
        $response = $this->actingAs($this->admin)->post(route('admin.blogs.store'), [
            'title' => 'Cách phối đồ công sở tinh tế',
            'category_id' => $bCat->id,
            'content' => 'Nội dung bài viết chi tiết...',
            'status' => 'published',
        ]);
        $response->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseHas('blogs', ['title' => 'Cách phối đồ công sở tinh tế']);

        $blog = Blog::where('title', 'Cách phối đồ công sở tinh tế')->first();

        // 2. Update Blog
        $update = $this->actingAs($this->admin)->put(route('admin.blogs.update', $blog), [
            'title' => 'Cách phối đồ công sở tinh tế 2026',
            'category_id' => $bCat->id,
            'content' => 'Nội dung bài viết chi tiết cập nhật...',
            'status' => 'published',
        ]);
        $update->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseHas('blogs', ['title' => 'Cách phối đồ công sở tinh tế 2026']);

        // 3. Delete Blog
        $delete = $this->actingAs($this->admin)->delete(route('admin.blogs.destroy', $blog));
        $delete->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
    }

    public function test_admin_can_manage_reviews(): void
    {
        $prod = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Áo Test Review',
            'slug' => 'ao-test-review',
            'sku' => 'TEST-REV',
            'price' => 200000,
            'stock' => 10,
            'status' => 'active',
        ]);

        $review = Review::create([
            'product_id' => $prod->id,
            'user_id' => $this->admin->id,
            'rating' => 5,
            'comment' => 'Sản phẩm rất đẹp',
            'status' => 'pending',
        ]);

        // Approve Review
        $approve = $this->actingAs($this->admin)->patch(route('admin.reviews.status', $review), [
            'status' => 'approved',
        ]);
        $approve->assertRedirect();
        $this->assertDatabaseHas('reviews', ['id' => $review->id, 'status' => 'approved']);

        // Delete Review
        $delete = $this->actingAs($this->admin)->delete(route('admin.reviews.destroy', $review));
        $delete->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'site_name' => 'NOVAWEAR OFFICIAL',
            'site_phone' => '0999 888 777',
            'shipping_fee' => '35000',
            'free_shipping_min' => '600000',
        ]);

        $response->assertRedirect();
        $this->assertEquals('NOVAWEAR OFFICIAL', Setting::get('site_name'));
        $this->assertEquals('0999 888 777', Setting::get('site_phone'));
        $this->assertEquals('35000', Setting::get('shipping_fee'));
    }
}
