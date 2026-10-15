<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;
    private Category $category;
    private Brand $brand;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);

        $this->category = Category::create([
            'name' => 'Áo Thun',
            'slug' => 'ao-thun',
            'status' => 'active',
        ]);

        $this->brand = Brand::create([
            'name' => 'NOVA Studio',
            'slug' => 'nova-studio',
            'status' => 'active',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name' => 'Áo Thun Cotton Tối Giản',
            'slug' => 'ao-thun-cotton-toi-gian',
            'sku' => 'NVW-0001',
            'description' => 'Mô tả chi tiết áo thun',
            'short_description' => 'Mô tả ngắn',
            'price' => 350000,
            'sale_price' => 290000,
            'stock' => 50,
            'status' => 'active',
            'featured' => true,
            'new_arrival' => true,
            'bestseller' => true,
        ]);

        ProductVariant::create([
            'product_id' => $this->product->id,
            'size' => 'L',
            'color' => 'Đen',
            'sku' => 'NVW-0001-L-BLK',
            'price' => 290000,
            'stock' => 20,
        ]);
    }

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('NOVAWEAR');
    }

    public function test_shop_page_renders_and_filters(): void
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('Áo Thun Cotton Tối Giản');

        $filtered = $this->get('/shop?category=ao-thun');
        $filtered->assertStatus(200);
        $filtered->assertSee('Áo Thun Cotton Tối Giản');
    }

    public function test_product_detail_page_renders(): void
    {
        $response = $this->get('/product/' . $this->product->slug);
        $response->assertStatus(200);
        $response->assertSee('Áo Thun Cotton Tối Giản');
        $response->assertSee('NVW-0001');
        $response->assertSee('290.000');
    }

    public function test_ajax_add_to_cart_and_update(): void
    {
        $response = $this->actingAs($this->customer)->postJson(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'size' => 'L',
            'color' => 'Đen',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $cartResponse = $this->actingAs($this->customer)->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('Áo Thun Cotton Tối Giản');
    }

    public function test_checkout_creates_real_order(): void
    {
        // Add to cart first
        $this->actingAs($this->customer)->postJson(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
            'size' => 'L',
            'color' => 'Đen',
        ]);

        $orderData = [
            'customer_name' => 'Nguyễn Văn A',
            'customer_phone' => '0987654321',
            'customer_email' => 'nguyenvana@example.com',
            'shipping_address' => '123 Đường Lê Lợi',
            'province' => 'TP. Hồ Chí Minh',
            'district' => 'Quận 1',
            'ward' => 'Phường Bến Nghé',
            'note' => 'Giao trong giờ hành chính',
            'payment_method' => 'cod',
        ];

        $response = $this->actingAs($this->customer)->post(route('checkout.store'), $orderData);
        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Nguyễn Văn A',
            'customer_phone' => '0987654321',
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name' => 'Áo Thun Cotton Tối Giản',
            'quantity' => 1,
        ]);
    }

    public function test_coupon_discount_can_be_applied(): void
    {
        Coupon::create([
            'code' => 'TEST10',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 100000,
            'status' => 'active',
        ]);

        $this->actingAs($this->customer)->postJson(route('cart.add'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customer)->post(route('cart.coupon.apply'), [
            'code' => 'TEST10',
        ]);

        $response->assertRedirect();
        $this->assertEquals('TEST10', session('applied_coupon'));
    }

    public function test_admin_route_protection(): void
    {
        // Guest is redirected to login
        $guestResponse = $this->get('/admin');
        $guestResponse->assertRedirect('/login');

        // Customer gets 403 Forbidden
        $customerResponse = $this->actingAs($this->customer)->get('/admin');
        $customerResponse->assertStatus(403);

        // Admin gets 200 OK
        $adminResponse = $this->actingAs($this->admin)->get('/admin');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Bảng Điều Khiển Quản Trị');
    }

    public function test_404_page_renders_custom_view(): void
    {
        $response = $this->get('/duong-dan-khong-ton-tai-123');
        $response->assertStatus(404);
        $response->assertSee('Trang Không Tồn Tại');
    }
}
