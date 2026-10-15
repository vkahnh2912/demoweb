<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;

class WishlistService
{
    public function getWishlist(): ?Wishlist
    {
        if (! Auth::check()) {
            return null;
        }

        return Wishlist::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function getItems()
    {
        $wishlist = $this->getWishlist();
        if (! $wishlist) {
            return collect();
        }

        return $wishlist->items()->with(['product.images', 'product.category'])->get();
    }

    public function getProductIds(): array
    {
        $wishlist = $this->getWishlist();
        if (! $wishlist) {
            return [];
        }

        return $wishlist->items()->pluck('product_id')->toArray();
    }

    public function toggle(int $productId): array
    {
        if (! Auth::check()) {
            return [
                'status' => 'unauthenticated',
                'message' => 'Vui lòng đăng nhập để lưu sản phẩm yêu thích.',
                'in_wishlist' => false,
                'count' => 0,
            ];
        }

        $wishlist = $this->getWishlist();
        $item = $wishlist->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->delete();
            $inWishlist = false;
            $message = 'Đã xóa sản phẩm khỏi danh sách yêu thích.';
        } else {
            $wishlist->items()->create(['product_id' => $productId]);
            $inWishlist = true;
            $message = 'Đã thêm sản phẩm vào danh sách yêu thích!';
        }

        $count = $wishlist->items()->count();

        return [
            'status' => 'success',
            'message' => $message,
            'in_wishlist' => $inWishlist,
            'count' => $count,
        ];
    }

    public function getCount(): int
    {
        $wishlist = $this->getWishlist();
        return $wishlist ? $wishlist->items()->count() : 0;
    }
}
