<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart(): Cart
    {
        if (Auth::check()) {
            $user = Auth::user();
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            // If there's an existing session cart, merge it into user cart
            $sessionId = Session::getId();
            $sessionCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
            if ($sessionCart && $sessionCart->id !== $cart->id) {
                foreach ($sessionCart->items as $item) {
                    $existingItem = $cart->items()
                        ->where('product_id', $item->product_id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->first();

                    if ($existingItem) {
                        $existingItem->update(['quantity' => $existingItem->quantity + $item->quantity]);
                    } else {
                        $item->update(['cart_id' => $cart->id]);
                    }
                }
                $sessionCart->delete();
            }

            return $cart;
        }

        // Guest user by session_id
        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function getItems()
    {
        $cart = $this->getCart();
        return $cart->items()->with(['product.images', 'variant'])->get();
    }

    public function getCount(): int
    {
        $cart = $this->getCart();
        return (int) $cart->items()->sum('quantity');
    }

    public function addItem(int $productId, ?int $variantId = null, int $quantity = 1, array $options = []): CartItem
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($productId);
        $price = $product->effective_price;

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)->find($variantId);
            if ($variant && $variant->price !== null && (float) $variant->price > 0) {
                $price = (float) $variant->price;
            }
            if ($variant) {
                $options['size'] = $variant->size;
                $options['color'] = $variant->color;
            }
        }

        $item = $cart->items()
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->price = $price;
            $item->options = $options;
            $item->save();
        } else {
            $item = $cart->items()->create([
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $price,
                'options' => $options,
            ]);
        }

        return $item;
    }

    public function updateQuantity(int $cartItemId, int $quantity): ?CartItem
    {
        $cart = $this->getCart();
        $item = $cart->items()->find($cartItemId);

        if (! $item) {
            return null;
        }

        if ($quantity <= 0) {
            $item->delete();
            return null;
        }

        $item->update(['quantity' => $quantity]);
        return $item;
    }

    public function removeItem(int $cartItemId): bool
    {
        $cart = $this->getCart();
        $item = $cart->items()->find($cartItemId);

        if ($item) {
            return (bool) $item->delete();
        }

        return false;
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        Session::forget('applied_coupon');
    }

    public function getTotals(): array
    {
        $cart = $this->getCart();
        $items = $cart->items;

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float) $item->price * $item->quantity;
        }

        $shippingThreshold = (float) Setting::get('free_shipping_min', 500000);
        $standardShipping = (float) Setting::get('shipping_fee', 30000);
        $shippingFee = ($subtotal >= $shippingThreshold || $subtotal == 0) ? 0 : $standardShipping;

        $discount = 0;
        $couponData = null;
        $appliedCouponCode = Session::get('applied_coupon');

        if ($appliedCouponCode) {
            $coupon = Coupon::where('code', $appliedCouponCode)->where('status', 'active')->first();
            if ($coupon && $coupon->isValid($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
                $couponData = $coupon;
            } else {
                Session::forget('applied_coupon');
            }
        }

        $total = max(0, $subtotal + $shippingFee - $discount);

        return [
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
            'total' => $total,
            'coupon' => $couponData,
            'free_shipping_threshold' => $shippingThreshold,
            'needed_for_free_shipping' => max(0, $shippingThreshold - $subtotal),
        ];
    }
}
