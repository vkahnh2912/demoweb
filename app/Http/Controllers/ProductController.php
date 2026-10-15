<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::with(['images', 'category', 'brand', 'variants', 'reviews.user'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Track recently viewed products in session
        $recentIds = session()->get('recently_viewed', []);
        if (($key = array_search($product->id, $recentIds)) !== false) {
            unset($recentIds[$key]);
        }
        array_unshift($recentIds, $product->id);
        $recentIds = array_slice($recentIds, 0, 5);
        session()->put('recently_viewed', $recentIds);

        // Fetch related products
        $relatedProducts = Product::with(['images', 'category'])
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // Fetch recently viewed products (excluding current)
        $recentlyViewed = Product::with(['images', 'category'])
            ->active()
            ->whereIn('id', array_diff($recentIds, [$product->id]))
            ->take(4)
            ->get();

        // Check if user has purchased this product to allow review
        $canReview = false;
        if (Auth::check()) {
            $canReview = Order::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->exists();
        }

        // Get unique sizes and colors from variants
        $variantsBySize = $product->variants->groupBy('size');
        $variantsByColor = $product->variants->groupBy('color');

        $ratingAvg = $product->reviews->avg('rating') ?: 5.0;
        $ratingCount = $product->reviews->count();

        return view('products.show', compact(
            'product',
            'relatedProducts',
            'recentlyViewed',
            'canReview',
            'variantsBySize',
            'variantsByColor',
            'ratingAvg',
            'ratingCount'
        ));
    }

    public function storeReview(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        if (! Auth::check()) {
            return redirect()->back()->with('error', 'Vui lòng đăng nhập để đánh giá sản phẩm.');
        }

        $canReview = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->exists();

        if (! $canReview) {
            return redirect()->back()->with('error', 'Chỉ khách hàng đã mua và nhận sản phẩm này thành công mới có thể đánh giá.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã gửi đánh giá cho sản phẩm!');
    }
}
