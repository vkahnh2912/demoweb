<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer): View
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $orders = $customer->orders()->latest()->paginate(10);
        $totalSpent = $customer->orders()->where('status', 'completed')->sum('total');
        $ordersCount = $customer->orders()->count();

        return view('admin.customers.show', compact('customer', 'orders', 'totalSpent', 'ordersCount'));
    }

    public function toggleStatus(User $customer)
    {
        $customer->status = ($customer->status === 'active') ? 'inactive' : 'active';
        $customer->save();

        return redirect()->back()->with('success', 'Đã thay đổi trạng thái tài khoản thành: ' . ($customer->status === 'active' ? 'Đang hoạt động' : 'Tạm khóa'));
    }
}
