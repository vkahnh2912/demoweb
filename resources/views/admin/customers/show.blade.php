@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-zinc-200">
        <div>
            <a href="{{ route('admin.customers.index') }}" class="font-semibold text-zinc-500 hover:text-black mb-1 inline-block">← Danh sách khách hàng</a>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Hồ Sơ Khách Hàng: {{ $customer->name }}</h1>
            <p class="text-zinc-500">Gia nhập ngày {{ $customer->created_at->format('d/m/Y') }}</p>
        </div>

        <form action="{{ route('admin.customers.toggle-status', $customer) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn đổi trạng thái tài khoản này?');">
            @csrf
            @method('PATCH')
            <button type="submit" class="px-4 py-2 border font-bold uppercase tracking-wider text-xs {{ $customer->status === 'active' ? 'bg-rose-50 border-rose-200 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 border-emerald-200 text-emerald-700 hover:bg-emerald-100' }}">
                {{ $customer->status === 'active' ? 'Khóa Tài Khoản Này' : 'Kích Hoạt Tài Khoản' }}
            </button>
        </form>
    </div>

    <!-- Customer Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 border border-zinc-200 shadow-sm">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Tổng Đơn Hàng</div>
            <div class="text-2xl font-bold text-zinc-900 mt-2">{{ $ordersCount }}</div>
        </div>
        <div class="bg-white p-5 border border-zinc-200 shadow-sm">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Tổng Chi Tiêu Hoàn Tất</div>
            <div class="text-2xl font-bold text-zinc-900 mt-2">{{ number_format($totalSpent) }}₫</div>
        </div>
        <div class="bg-white p-5 border border-zinc-200 shadow-sm">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Trạng Thái Tài Khoản</div>
            <div class="mt-2">
                @if($customer->status === 'active')
                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-200">Đang hoạt động</span>
                @else
                    <span class="inline-block px-3 py-1 bg-rose-50 text-rose-700 font-bold text-xs border border-rose-200">Tạm khóa</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="bg-white p-5 border border-zinc-200 shadow-sm space-y-3">
        <h2 class="font-bold uppercase tracking-wider text-zinc-800 pb-2 border-b border-zinc-100">Thông Tin Liên Hệ</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <span class="text-zinc-400">Email:</span>
                <span class="font-semibold text-zinc-900 ml-1">{{ $customer->email }}</span>
            </div>
            <div>
                <span class="text-zinc-400">Số điện thoại:</span>
                <span class="font-semibold text-zinc-900 ml-1">{{ $customer->phone ?? 'Chưa cập nhật' }}</span>
            </div>
            <div>
                <span class="text-zinc-400">Xác thực email:</span>
                <span class="font-semibold text-zinc-900 ml-1">{{ $customer->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}</span>
            </div>
        </div>
    </div>

    <!-- Orders History -->
    <div class="bg-white border border-zinc-200 shadow-sm">
        <div class="p-4 border-b border-zinc-100 font-bold uppercase tracking-wider text-zinc-800">
            Lịch Sử Mua Hàng
        </div>
        <table class="w-full text-left">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="p-3.5">Mã đơn</th>
                    <th class="p-3.5">Ngày mua</th>
                    <th class="p-3.5">Tổng tiền</th>
                    <th class="p-3.5">Thanh toán</th>
                    <th class="p-3.5">Trạng thái</th>
                    <th class="p-3.5 text-right">Chi tiết</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-zinc-50">
                        <td class="p-3.5 font-bold font-mono">
                            <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">#{{ $order->order_number }}</a>
                        </td>
                        <td class="p-3.5 text-zinc-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3.5 font-bold text-zinc-900">{{ number_format($order->total) }}₫</td>
                        <td class="p-3.5 uppercase text-[10px] font-semibold text-zinc-600">{{ $order->payment_method }}</td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 border font-bold text-[10px] uppercase bg-zinc-100 text-zinc-700">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 font-semibold uppercase text-[11px]">Xem →</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-zinc-400">Khách hàng chưa có đơn hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $orders->links() }}
    </div>
</div>
@endsection
