<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService
    ) {}

    public function index()
    {
        $items = $this->cartService->getItems();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng của bạn đang trống.');
        }

        $totals = $this->cartService->getTotals();
        $user = Auth::user();
        $defaultAddress = null;

        if ($user) {
            $defaultAddress = Address::where('user_id', $user->id)->where('is_default', true)->first()
                ?? Address::where('user_id', $user->id)->first();
        }

        return view('checkout.index', compact('items', 'totals', 'user', 'defaultAddress'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:500',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,bank_transfer',
        ]);

        try {
            $order = $this->orderService->createOrder($validated);

            // If user checked save address and is logged in
            if (Auth::check() && $request->boolean('save_address')) {
                Address::create([
                    'user_id' => Auth::id(),
                    'full_name' => $validated['customer_name'],
                    'phone' => $validated['customer_phone'],
                    'address' => $validated['shipping_address'],
                    'province' => $validated['province'],
                    'district' => $validated['district'],
                    'ward' => $validated['ward'],
                ]);
            }

            return redirect()->route('checkout.success', $order->order_number);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function success(string $orderNumber): View
    {
        $order = Order::with(['items.product', 'coupon'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('checkout.success', compact('order'));
    }
}
