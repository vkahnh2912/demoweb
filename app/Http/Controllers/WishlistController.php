<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function __construct(
        protected WishlistService $wishlistService
    ) {}

    public function index(): View
    {
        if (! Auth::check()) {
            return view('wishlist.guest');
        }

        $items = $this->wishlistService->getItems();
        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $res = $this->wishlistService->toggle($validated['product_id']);
        return response()->json($res);
    }
}
