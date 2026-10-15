<?php

namespace App\Http\Controllers;

use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['images', 'category', 'brand'])->active();

        // 1. Filter by category
        if ($request->filled('category')) {
            $catSlug = $request->query('category');
            $category = Category::where('slug', $catSlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // 2. Filter by brand
        if ($request->filled('brand')) {
            $brandSlug = $request->query('brand');
            $brand = Brand::where('slug', $brandSlug)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        // 3. Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->query('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->query('max_price'));
        }

        // 4. Filter by stock
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // 5. Special filters (sale, new, bestseller)
        if ($request->query('filter') === 'sale') {
            $query->whereNotNull('sale_price')->whereRaw('sale_price < price');
        } elseif ($request->query('filter') === 'new') {
            $query->where('new_arrival', true);
        } elseif ($request->query('filter') === 'bestseller') {
            $query->where('bestseller', true);
        }

        // 6. Filter by size or color (via variants)
        if ($request->filled('size')) {
            $size = $request->query('size');
            $query->whereHas('variants', function ($q) use ($size) {
                $q->where('size', $size);
            });
        }
        if ($request->filled('color')) {
            $color = $request->query('color');
            $query->whereHas('variants', function ($q) use ($color) {
                $q->where('color', $color);
            });
        }

        // 7. Search query if passed to shop
        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 8. Sorting
        $sort = $request->query('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'bestselling':
                $query->orderBy('bestseller', 'desc')->latest();
                break;
            case 'featured':
                $query->orderBy('featured', 'desc')->latest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Data for sidebar filters
        $categories = Category::withCount('products')->where('status', 'active')->orderBy('sort_order')->get();
        $brands = Brand::withCount('products')->where('status', 'active')->get();
        $availableSizes = ['S', 'M', 'L', 'XL'];
        $availableColors = [
            ['name' => 'Đen', 'code' => '#111111'],
            ['name' => 'Trắng', 'code' => '#FFFFFF'],
            ['name' => 'Xám', 'code' => '#71717A'],
            ['name' => 'Be', 'code' => '#D4B996'],
            ['name' => 'Xanh Navy', 'code' => '#1E293B'],
        ];

        $currentCategory = $request->filled('category') ? Category::where('slug', $request->query('category'))->first() : null;

        return view('shop.index', compact(
            'products',
            'categories',
            'brands',
            'availableSizes',
            'availableColors',
            'currentCategory',
            'sort'
        ));
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        request()->merge(['category' => $slug]);
        return $this->index(request());
    }
}
