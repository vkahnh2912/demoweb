<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - NOVAWEAR Fashion E-commerce
|--------------------------------------------------------------------------
*/

// --- Frontend Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category.show');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/{slug}/review', [ProductController::class, 'storeReview'])->name('product.review.store');

Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::get('/cart/mini', [CartController::class, 'miniCart'])->name('cart.mini');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{order_number}', [CheckoutController::class, 'success'])->name('checkout.success');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

// SEO Sitemap & Robots
Route::get('/sitemap.xml', function () {
    $products = Product::active()->get();
    $categories = Category::where('status', 'active')->get();
    $blogs = Blog::where('status', 'published')->get();

    $content = view('seo.sitemap', compact('products', 'categories', 'blogs'))->render();
    return Response::make($content, 200, ['Content-Type' => 'text/xml']);
})->name('sitemap');

Route::get('/robots.txt', function () {
    $robots = "User-agent: *\nDisallow: /admin/\nDisallow: /account/\nDisallow: /cart/\nDisallow: /checkout/\n\nSitemap: " . url('/sitemap.xml');
    return Response::make($robots, 200, ['Content-Type' => 'text/plain']);
})->name('robots');

// --- User Account Routes (Auth required) ---
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order_number}', [AccountController::class, 'orderDetail'])->name('orders.detail');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])->name('addresses.destroy');
    Route::put('/password', [AccountController::class, 'changePassword'])->name('password.change');
});

// Default Breeze Dashboard redirect
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('account.dashboard');
})->middleware('auth')->name('dashboard');

// Breeze Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Admin Panel Routes (Auth + Admin middleware) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.primary');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.destroy');

    // Categories & Brands
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('brands', AdminBrandController::class);

    // Orders
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Customers
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::patch('customers/{customer}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('customers.toggle-status');

    // Marketing & Content
    Route::resource('coupons', AdminCouponController::class);
    Route::resource('banners', AdminBannerController::class);
    Route::resource('blogs', AdminBlogController::class);

    // Reviews
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.status');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Settings
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
