@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Tài Khoản Của Tôi']]" />

    <div class="py-4 border-b border-zinc-200 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
                Tài Khoản Của Tôi
            </h1>
            <p class="text-xs text-zinc-500 mt-1">Xin chào, <strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
        </div>
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-5 py-2 border border-zinc-300 hover:border-rose-600 text-zinc-700 hover:text-rose-600 text-xs uppercase font-semibold tracking-wider transition-colors">
                    Đăng Xuất
                </button>
            </form>
        </div>
    </div>

    <!-- Main Account Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Navigation Sidebar (3 cols) -->
        <aside class="lg:col-span-3 space-y-1 text-xs">
            <a href="{{ route('account.dashboard') }}" class="block px-4 py-3 bg-zinc-950 text-white font-bold uppercase tracking-wider">
                Tổng Quan
            </a>
            <a href="{{ route('account.orders') }}" class="block px-4 py-3 hover:bg-zinc-100 text-zinc-700 font-semibold uppercase tracking-wider transition-colors">
                Lịch Sử Đơn Hàng ({{ $ordersCount }})
            </a>
            <a href="{{ route('account.addresses') }}" class="block px-4 py-3 hover:bg-zinc-100 text-zinc-700 font-semibold uppercase tracking-wider transition-colors">
                Sổ Địa Chỉ ({{ $addressesCount }})
            </a>
            <a href="{{ route('wishlist.index') }}" class="block px-4 py-3 hover:bg-zinc-100 text-zinc-700 font-semibold uppercase tracking-wider transition-colors">
                Danh Sách Yêu Thích
            </a>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 hover:bg-zinc-100 text-zinc-700 font-semibold uppercase tracking-wider transition-colors">
                Thông Tin Cá Nhân
            </a>
        </aside>

        <!-- Main Dashboard Content (9 cols) -->
        <main class="lg:col-span-9 space-y-8">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-6 bg-zinc-50 border border-zinc-200">
                    <span class="text-xs uppercase font-bold tracking-wider text-zinc-400 block">Tổng Đơn Hàng</span>
                    <span class="text-3xl font-extrabold text-zinc-950 mt-2 block">{{ $ordersCount }}</span>
                </div>
                <div class="p-6 bg-zinc-50 border border-zinc-200">
                    <span class="text-xs uppercase font-bold tracking-wider text-zinc-400 block">Đơn Giao Thành Công</span>
                    <span class="text-3xl font-extrabold text-emerald-600 mt-2 block">{{ $completedCount }}</span>
                </div>
                <div class="p-6 bg-zinc-50 border border-zinc-200">
                    <span class="text-xs uppercase font-bold tracking-wider text-zinc-400 block">Địa Chỉ Đã Lưu</span>
                    <span class="text-3xl font-extrabold text-zinc-950 mt-2 block">{{ $addressesCount }}</span>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="border border-zinc-200 bg-white">
                <div class="p-5 border-b border-zinc-200 flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950">Đơn Hàng Gần Đây</h2>
                    <a href="{{ route('account.orders') }}" class="text-xs font-semibold text-zinc-900 hover:underline">
                        Xem tất cả →
                    </a>
                </div>

                @if($recentOrders->isEmpty())
                    <div class="p-8 text-center text-xs text-zinc-500">
                        Bạn chưa đặt đơn hàng nào gần đây.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 text-[11px] uppercase">
                                <tr>
                                    <th class="py-3 px-4">Mã đơn</th>
                                    <th class="py-3 px-4">Ngày đặt</th>
                                    <th class="py-3 px-4">Số lượng</th>
                                    <th class="py-3 px-4">Tổng tiền</th>
                                    <th class="py-3 px-4">Trạng thái</th>
                                    <th class="py-3 px-4 text-right">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="py-3.5 px-4 font-mono font-bold text-zinc-900">#{{ $order->order_number }}</td>
                                        <td class="py-3.5 px-4 text-zinc-600">{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td class="py-3.5 px-4 text-zinc-600">{{ $order->items->sum('quantity') }} món</td>
                                        <td class="py-3.5 px-4 font-bold text-zinc-900">{{ number_format($order->total) }}₫</td>
                                        <td class="py-3.5 px-4">
                                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider
                                                {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                                {{ $order->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                                {{ in_array($order->status, ['confirmed', 'processing', 'shipping']) ? 'bg-sky-50 text-sky-700' : '' }}
                                                {{ $order->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : '' }}
                                            ">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <a href="{{ route('account.orders.detail', $order->order_number) }}" class="font-bold text-zinc-900 hover:underline">
                                                Xem →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Password Change Section -->
            <div class="border border-zinc-200 bg-white p-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-950 mb-4 pb-2 border-b border-zinc-100">
                    Đổi Mật Khẩu
                </h3>
                <form action="{{ route('account.password.change') }}" method="POST" class="max-w-md space-y-4 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-semibold text-zinc-700 mb-1">Mật khẩu hiện tại *</label>
                        <input type="password" name="current_password" required class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                        @error('current_password') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-semibold text-zinc-700 mb-1">Mật khẩu mới *</label>
                        <input type="password" name="password" required class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                        @error('password') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-semibold text-zinc-700 mb-1">Nhập lại mật khẩu mới *</label>
                        <input type="password" name="password_confirmation" required class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs uppercase font-semibold tracking-wider transition-colors">
                        Lưu Mật Khẩu Mới
                    </button>
                </form>
            </div>

        </main>
    </div>
</div>
@endsection
