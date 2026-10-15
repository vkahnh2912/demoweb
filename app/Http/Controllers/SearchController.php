<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim($request->query('q', ''));

        $products = Product::with(['images', 'category'])
            ->active()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhereHas('category', function ($catQ) use ($q) {
                            $catQ->where('name', 'like', "%{$q}%");
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('search.index', compact('products', 'q'));
    }

    public function suggestions(Request $request): JsonResponse
    {
        $q = trim($request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = Product::with('images')
            ->active()
            ->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            })
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'price' => number_format($p->effective_price) . '₫',
                    'original_price' => $p->sale_price ? number_format($p->price) . '₫' : null,
                    'image' => $p->primary_image_url,
                    'url' => route('product.show', $p->slug),
                ];
            });

        return response()->json($results);
    }
}
