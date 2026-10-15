<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'brand', 'images']);

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'required|string|unique:products,sku|max:50',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,draft,out_of_stock',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'bestseller' => 'nullable|boolean',
            'new_arrival' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_urls' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(5);
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => strtoupper($validated['sku']),
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'featured' => $request->boolean('featured'),
            'bestseller' => $request->boolean('bestseller'),
            'new_arrival' => $request->boolean('new_arrival'),
            'meta_title' => $validated['meta_title'] ?? $validated['name'],
            'meta_description' => $validated['meta_description'] ?? null,
        ]);

        // Handle uploaded file images
        $isFirst = true;
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $idx + 1,
                    'is_primary' => $isFirst,
                ]);
                $isFirst = false;
            }
        }

        // Handle image URLs (comma/newline separated)
        if ($request->filled('image_urls')) {
            $urls = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $request->input('image_urls'))));
            foreach ($urls as $idx => $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $url,
                    'sort_order' => $idx + 10,
                    'is_primary' => $isFirst,
                ]);
                $isFirst = false;
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Đã tạo sản phẩm mới thành công!');
    }

    public function edit(Product $product): View
    {
        $product->load(['images', 'variants']);
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,draft,out_of_stock',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'featured' => 'nullable|boolean',
            'bestseller' => 'nullable|boolean',
            'new_arrival' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_urls' => 'nullable|string',
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'sku' => strtoupper($validated['sku']),
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'stock' => $validated['stock'],
            'status' => $validated['status'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'featured' => $request->boolean('featured'),
            'bestseller' => $request->boolean('bestseller'),
            'new_arrival' => $request->boolean('new_arrival'),
            'meta_title' => $validated['meta_title'] ?? $validated['name'],
            'meta_description' => $validated['meta_description'] ?? null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $product->images()->count() + 1,
                    'is_primary' => ! $product->images()->where('is_primary', true)->exists(),
                ]);
            }
        }

        if ($request->filled('image_urls')) {
            $urls = array_filter(array_map('trim', preg_split('/[\r\n,]+/', $request->input('image_urls'))));
            foreach ($urls as $url) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $url,
                    'sort_order' => $product->images()->count() + 1,
                    'is_primary' => ! $product->images()->where('is_primary', true)->exists(),
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm thành công!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm thành công!');
    }

    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Đã đặt làm ảnh đại diện chính.');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        $image->delete();
        return redirect()->back()->with('success', 'Đã xóa ảnh sản phẩm.');
    }
}
