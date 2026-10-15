<?php

namespace App\Services;

use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $cartItems = $this->cartService->getItems();
            if ($cartItems->isEmpty()) {
                throw new \Exception('Giỏ hàng của bạn đang trống.');
            }

            $totals = $this->cartService->getTotals();
            $user = Auth::user();

            $orderNumber = 'NVW' . date('ymd') . strtoupper(Str::random(5));

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user?->id,
                'coupon_id' => $totals['coupon']?->id,
                'customer_name' => $data['customer_name'],
                'customer_phone' => $data['customer_phone'],
                'customer_email' => $data['customer_email'],
                'shipping_address' => $data['shipping_address'],
                'province' => $data['province'] ?? null,
                'district' => $data['district'] ?? null,
                'ward' => $data['ward'] ?? null,
                'note' => $data['note'] ?? null,
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount'],
                'shipping_fee' => $totals['shipping_fee'],
                'total' => $totals['total'],
                'payment_method' => $data['payment_method'] ?? 'cod',
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->variant?->sku ?? $item->product->sku,
                    'product_image' => $item->product->primary_image_url,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->price * $item->quantity,
                    'options' => $item->options,
                ]);

                // Reduce stock
                if ($item->product_variant_id && $item->variant) {
                    $item->variant->decrement('stock', min($item->quantity, $item->variant->stock));
                }
                $item->product->decrement('stock', min($item->quantity, $item->product->stock));
            }

            // Coupon usage recording
            if ($totals['coupon']) {
                $coupon = $totals['coupon'];
                $coupon->increment('used_count');
                if ($user) {
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'discount_amount' => $totals['discount'],
                    ]);
                }
            }

            // Create initial payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $order->payment_method,
                'amount' => $order->total,
                'status' => 'pending',
            ]);

            // Clear Cart
            $this->cartService->clearCart();

            return $order;
        });
    }
}
