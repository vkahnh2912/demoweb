@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Quản Lý Đơn Hàng</h1>
            <p class="text-zinc-500 mt-1">Danh sách đơn đặt hàng từ khách hàng trên hệ thống</p>
        </div>
        
        <!-- Filter Tabs / Search -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Mã đơn / Tên / SĐT..." class="text-xs p-2 border-zinc-300 focus:border-zinc-950 focus:ring-0 w-48 sm:w-64" />
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}" />
            @endif
            <button type="submit" class="px-4 py-2 bg-zinc-900 text-white font-semibold uppercase tracking-wider">Lọc</button>
            @if(request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="p-2 text-zinc-400 hover:text-black">✕ Xóa lọc</a>
            @endif
        </form>
    </div>

    <!-- Quick Status Filters -->
    <div class="flex flex-wrap gap-2 text-xs">
        @php
            $statuses = [
                '' => 'Tất cả',
                'pending' => 'Chờ xử lý',
                'confirmed' => 'Đã xác nhận',
                'processing' => 'Đang đóng gói',
                'shipping' => 'Đang giao hàng',
                'completed' => 'Hoàn tất',
                'cancelled' => 'Đã hủy',
            ];
        @endphp
        @foreach($statuses as $key => $label)
            <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => $key ?: null])) }}"
               class="px-3 py-1.5 border {{ (request('status') == $key && ($key !== '' || !request()->has('status'))) ? 'bg-zinc-950 text-white border-zinc-950 font-bold' : 'bg-white text-zinc-600 border-zinc-200 hover:border-zinc-400' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Orders Table -->
    <div class="bg-white border border-zinc-200 shadow-sm overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="p-3.5">Mã đơn</th>
                    <th class="p-3.5">Khách hàng</th>
                    <th class="p-3.5">Điện thoại</th>
                    <th class="p-3.5">Tổng tiền</th>
                    <th class="p-3.5">Phương thức</th>
                    <th class="p-3.5">Thanh toán</th>
                    <th class="p-3.5">Trạng thái</th>
                    <th class="p-3.5">Ngày tạo</th>
                    <th class="p-3.5 text-right">Chi tiết</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-zinc-50">
                        <td class="p-3.5 font-bold font-mono text-zinc-900">
                            <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td class="p-3.5 font-semibold text-zinc-800">
                            <div>{{ $order->customer_name }}</div>
                            <div class="text-[10px] text-zinc-400">{{ $order->customer_email }}</div>
                        </td>
                        <td class="p-3.5 text-zinc-600">{{ $order->customer_phone }}</td>
                        <td class="p-3.5 font-bold text-zinc-900">{{ number_format($order->total) }}₫</td>
                        <td class="p-3.5 uppercase font-semibold text-[10px] text-zinc-600">{{ $order->payment_method }}</td>
                        <td class="p-3.5">
                            @if($order->payment_status === 'paid')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">Đã thanh toán</span>
                            @elseif($order->payment_status === 'failed')
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-700 font-bold text-[10px] border border-rose-200">Thất bại</span>
                            @else
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-bold text-[10px] border border-amber-200">Chờ thanh toán</span>
                            @endif
                        </td>
                        <td class="p-3.5">
                            @php
                                $badgeStyles = [
                                    'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
                                    'confirmed' => 'bg-sky-50 text-sky-800 border-sky-200',
                                    'processing' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                    'shipping' => 'bg-purple-50 text-purple-800 border-purple-200',
                                    'completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                    'cancelled' => 'bg-rose-50 text-rose-800 border-rose-200',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 border font-bold text-[10px] uppercase {{ $badgeStyles[$order->status] ?? 'bg-zinc-100 text-zinc-700' }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="p-3.5 text-zinc-400 text-[11px]">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3.5 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-900 font-semibold uppercase text-[11px]">
                                Xem →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-zinc-400">Không tìm thấy đơn hàng nào.</td>
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
