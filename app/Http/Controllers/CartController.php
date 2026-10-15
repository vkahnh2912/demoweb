<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    public function index(): View
    {
        $items = $this->cartService->getItems();
        $totals = $this->cartService->getTotals();

        return view('cart.index', compact('items', 'totals'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
            'options' => 'nullable|array',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variantId = $validated['product_variant_id'] ?? null;
        $options = $validated['options'] ?? [];

        $item = $this->cartService->addItem($validated['product_id'], $variantId, $quantity, $options);
        $count = $this->cartService->getCount();
        $totals = $this->cartService->getTotals();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
                'count' => $count,
                'item' => $item,
                'totals' => $totals,
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = $this->cartService->updateQuantity($validated['cart_item_id'], $validated['quantity']);
        $totals = $this->cartService->getTotals();
        $count = $this->cartService->getCount();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật số lượng thành công!',
                'item_subtotal' => $item ? number_format($item->subtotal) . '₫' : 0,
                'totals' => [
                    'subtotal' => number_format($totals['subtotal']) . '₫',
                    'shipping_fee' => $totals['shipping_fee'] > 0 ? number_format($totals['shipping_fee']) . '₫' : 'Miễn phí',
                    'discount' => number_format($totals['discount']) . '₫',
                    'total' => number_format($totals['total']) . '₫',
                ],
                'count' => $count,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng.');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        $this->cartService->removeItem($validated['cart_item_id']);
        $totals = $this->cartService->getTotals();
        $count = $this->cartService->getCount();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                'totals' => [
                    'subtotal' => number_format($totals['subtotal']) . '₫',
                    'shipping_fee' => $totals['shipping_fee'] > 0 ? number_format($totals['shipping_fee']) . '₫' : 'Miễn phí',
                    'discount' => number_format($totals['discount']) . '₫',
                    'total' => number_format($totals['total']) . '₫',
                ],
                'count' => $count,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($validated['code']));
        $coupon = Coupon::where('code', $code)->where('status', 'active')->first();

        if (! $coupon) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.'], 422);
            }
            return redirect()->back()->with('error', 'Mã giảm giá không tồn tại hoặc đã hết hạn.');
        }

        $totals = $this->cartService->getTotals();
        if (! $coupon->isValid($totals['subtotal'])) {
            $msg = 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($coupon->min_order_amount) . '₫ để áp dụng mã này.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        session()->put('applied_coupon', $code);
        $newTotals = $this->cartService->getTotals();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Áp dụng mã giảm giá ' . $code . ' thành công!',
                'totals' => [
                    'subtotal' => number_format($newTotals['subtotal']) . '₫',
                    'shipping_fee' => $newTotals['shipping_fee'] > 0 ? number_format($newTotals['shipping_fee']) . '₫' : 'Miễn phí',
                    'discount' => number_format($newTotals['discount']) . '₫',
                    'total' => number_format($newTotals['total']) . '₫',
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Áp dụng mã giảm giá thành công!');
    }

    public function removeCoupon(Request $request)
    {
        session()->forget('applied_coupon');
        return redirect()->back()->with('success', 'Đã gỡ mã giảm giá.');
    }

    public function miniCart(): JsonResponse
    {
        $items = $this->cartService->getItems();
        $totals = $this->cartService->getTotals();
        $count = $this->cartService->getCount();

        return response()->json([
            'count' => $count,
            'items' => $items,
            'totals' => $totals,
        ]);
    }
}
