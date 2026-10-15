@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    <!-- KPI STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Revenue -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Tổng Doanh Thu</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-zinc-950">{{ number_format($revenue) }}₫</span>
                <span class="text-emerald-600 font-bold text-xs">+15.2%</span>
            </div>
            <span class="text-[11px] text-zinc-400 mt-2 block">Từ các đơn hoàn thành</span>
        </div>

        <!-- Orders -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Tổng Đơn Hàng</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-zinc-950">{{ $totalOrders }}</span>
                <span class="text-amber-600 font-bold text-xs">{{ $pendingOrders }} chờ xử lý</span>
            </div>
            <span class="text-[11px] text-zinc-400 mt-2 block">{{ $completedOrders }} đơn đã giao xong</span>
        </div>

        <!-- Customers -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Khách Hàng</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-zinc-950">{{ $totalCustomers }}</span>
                <span class="text-sky-600 font-bold text-xs">Thành viên</span>
            </div>
            <span class="text-[11px] text-zinc-400 mt-2 block">Tài khoản khách hàng hoạt động</span>
        </div>

        <!-- Products -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Sản Phẩm Trong Kho</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-extrabold text-zinc-950">{{ $totalProducts }}</span>
                <a href="{{ route('admin.products.create') }}" class="text-xs font-semibold text-zinc-900 hover:underline">+ Thêm mới</a>
            </div>
            <span class="text-[11px] text-zinc-400 mt-2 block">Đang hiển thị trên website</span>
        </div>
    </div>

    <!-- REVENUE BY MONTH CHART & TOP PRODUCTS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Revenue by Month Chart (8 cols) -->
        <div class="lg:col-span-8 bg-white border border-zinc-200 p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-zinc-100 pb-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">Doanh Thu Theo Tháng</h3>
                <span class="text-xs text-zinc-400">Đơn vị: VNĐ</span>
            </div>

            <!-- Visual Bar Chart -->
            <div class="h-64 flex items-end justify-between gap-4 pt-8 px-4 border-b border-zinc-200 pb-2">
                @forelse($monthlyRevenue as $rev)
                    @php
                        $maxRev = $monthlyRevenue->max('revenue') ?: 1;
                        $percent = round(($rev->revenue / $maxRev) * 100);
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-2 group h-full justify-end">
                        <div class="text-[10px] font-bold text-zinc-700 opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ number_format($rev->revenue) }}₫
                        </div>
                        <div class="w-full max-w-[48px] bg-zinc-900 hover:bg-zinc-700 transition-all rounded-t-sm"
                             style="height: {{ max(10, $percent) }}%"></div>
                        <span class="text-[11px] font-semibold text-zinc-500">{{ $rev->month }}</span>
                    </div>
                @empty
                    <div class="w-full h-full flex items-center justify-center text-xs text-zinc-400">
                        Chưa có dữ liệu thống kê doanh thu theo tháng.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Selling / Low stock products (4 cols) -->
        <div class="lg:col-span-4 bg-white border border-zinc-200 p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-4">
                Sản Phẩm Tiêu Biểu
            </h3>

            <div class="divide-y divide-zinc-100 text-xs">
                @foreach($topProducts as $tp)
                    <div class="py-3 flex items-center gap-3">
                        <img src="{{ $tp->primary_image_url }}" alt="{{ $tp->name }}" class="w-12 h-14 object-cover bg-zinc-100 flex-shrink-0" />
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-zinc-900 truncate">{{ $tp->name }}</h4>
                            <span class="text-zinc-500 font-mono text-[11px]">{{ $tp->sku }}</span>
                            <div class="flex justify-between items-center mt-1">
                                <span class="font-semibold text-zinc-900">{{ number_format($tp->effective_price) }}₫</span>
                                <span class="text-[11px] {{ $tp->stock < 20 ? 'text-amber-600 font-bold' : 'text-zinc-400' }}">Kho: {{ $tp->stock }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="bg-white border border-zinc-200 shadow-sm">
        <div class="p-6 border-b border-zinc-100 flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">Đơn Hàng Mới Nhất</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-zinc-900 hover:underline">
                Xem tất cả đơn hàng →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 text-[11px] uppercase">
                    <tr>
                        <th class="py-3 px-6">Mã đơn</th>
                        <th class="py-3 px-6">Khách hàng</th>
                        <th class="py-3 px-6">Số điện thoại</th>
                        <th class="py-3 px-6">Tổng tiền</th>
                        <th class="py-3 px-6">Thanh toán</th>
                        <th class="py-3 px-6">Trạng thái</th>
                        <th class="py-3 px-6 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-zinc-50/50">
                            <td class="py-4 px-6 font-mono font-bold text-zinc-900">#{{ $order->order_number }}</td>
                            <td class="py-4 px-6 font-semibold text-zinc-900">{{ $order->customer_name }}</td>
                            <td class="py-4 px-6 text-zinc-600">{{ $order->customer_phone }}</td>
                            <td class="py-4 px-6 font-bold text-zinc-950">{{ number_format($order->total) }}₫</td>
                            <td class="py-4 px-6">
                                <span class="text-[11px] {{ $order->payment_status === 'paid' ? 'text-emerald-700 font-bold' : 'text-zinc-500' }}">
                                    {{ $order->payment_method_label }} ({{ $order->payment_status_label }})
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider
                                    {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $order->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                    {{ in_array($order->status, ['confirmed', 'processing', 'shipping']) ? 'bg-sky-50 text-sky-700' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : '' }}
                                ">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-bold text-zinc-900 hover:underline">
                                    Chi tiết →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
