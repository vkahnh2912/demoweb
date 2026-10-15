<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        $recentOrders = Order::with('items')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $ordersCount = Order::where('user_id', $user->id)->count();
        $completedCount = Order::where('user_id', $user->id)->where('status', 'completed')->count();
        $addressesCount = Address::where('user_id', $user->id)->count();

        return view('account.dashboard', compact('user', 'recentOrders', 'ordersCount', 'completedCount', 'addressesCount'));
    }

    public function orders(): View
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber): View
    {
        $order = Order::with(['items.product.images', 'coupon', 'payments'])
            ->where('user_id', Auth::id())
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('account.order-detail', compact('order'));
    }

    public function addresses(): View
    {
        $addresses = Address::where('user_id', Auth::id())->latest()->get();
        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'ward' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => Auth::id(),
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'province' => $validated['province'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->back()->with('success', 'Đã thêm địa chỉ giao hàng mới!');
    }

    public function deleteAddress(int $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Đã xóa địa chỉ thành công.');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật mật khẩu mới thành công!');
    }
}
