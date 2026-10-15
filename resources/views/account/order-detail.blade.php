@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Tài Khoản', 'url' => route('account.dashboard')],
        ['label' => 'Đơn Hàng', 'url' => route('account.orders')],
        ['label' => '#' . $order->order_number]
    ]" />

    <div class="py-4 border-b border-zinc-200 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
                Chi Tiết Đơn Hàng #{{ $order->order_number }}
            </h1>
            <p class="text-xs text-zinc-500 mt-1">Ngày đặt hàng: {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-block px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider
                {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : '' }}
                {{ $order->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                {{ in_array($order->status, ['confirmed', 'processing', 'shipping']) ? 'bg-sky-50 text-sky-700' : '' }}
                {{ $order->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : '' }}
            ">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-xs">

        <!-- ORDER ITEMS (2 COLS) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="border border-zinc-200 bg-white p-6 space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-100">
                    Sản Phẩm Đã Mua ({{ $order->items->sum('quantity') }})
                </h2>

                <div class="divide-y divide-zinc-100">
                    @foreach($order->items as $item)
                        <div class="py-4 flex gap-4">
                            <img src="{{ $item->product?->primary_image_url ?? $item->product_image ?? 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=400&q=80' }}"
                                 alt="{{ $item->product_name }}"
                                 class="w-16 h-20 object-cover bg-zinc-100 flex-shrink-0" />

                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-zinc-900 text-sm">
                                        @if($item->product)
                                            <a href="{{ route('product.show', $item->product->slug) }}" class="hover:underline">{{ $item->product_name }}</a>
                                        @else
                                            {{ $item->product_name }}
                                        @endif
                                    </h3>
                                    <p class="text-zinc-500 text-[11px] mt-0.5">Mã SKU: {{ $item->product_sku }}</p>
                                    @if($item->options && (isset($item->options['size']) || isset($item->options['color'])))
                                        <p class="text-[11px] text-zinc-500">
                                            {{ isset($item->options['size']) ? 'Kích cỡ: ' . $item->options['size'] : '' }}
                                            {{ isset($item->options['color']) ? ' | Màu: ' . $item->options['color'] : '' }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-zinc-500">{{ number_format($item->price) }}₫ x {{ $item->quantity }}</span>
                                    <span class="font-bold text-zinc-950">{{ number_format($item->total) }}₫</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Note if any -->
            @if($order->note)
                <div class="p-4 bg-zinc-50 border border-zinc-200 text-xs">
                    <span class="font-bold text-zinc-700 block mb-1">Ghi chú giao hàng:</span>
                    <p class="text-zinc-600">{{ $order->note }}</p>
                </div>
            @endif
        </div>

        <!-- ORDER SUMMARY & ADDRESS (1 COL) -->
        <div class="space-y-6">

            <!-- Summary -->
            <div class="border border-zinc-200 bg-white p-6 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-100">
                    Chi Tiết Thanh Toán
                </h3>
                <div class="space-y-2.5">
                    <div class="flex justify-between text-zinc-600">
                        <span>Tạm tính</span>
                        <span class="font-semibold text-zinc-900">{{ number_format($order->subtotal) }}₫</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-rose-600">
                            <span>Giảm giá</span>
                            <span class="font-semibold">-{{ number_format($order->discount_amount) }}₫</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-zinc-600">
                        <span>Phí vận chuyển</span>
                        <span class="font-semibold text-zinc-900">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee) . '₫' : 'Miễn phí' }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-extrabold text-zinc-950 pt-3 border-t border-zinc-100">
                        <span>Tổng thanh toán</span>
                        <span>{{ number_format($order->total) }}₫</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-zinc-100 space-y-1">
                    <p class="text-zinc-500">Phương thức: <strong class="text-zinc-800">{{ $order->payment_method_label }}</strong></p>
                    <p class="text-zinc-500">Tình trạng: <strong class="text-zinc-800">{{ $order->payment_status_label }}</strong></p>
                </div>
            </div>

            <!-- Shipping Destination -->
            <div class="border border-zinc-200 bg-white p-6 space-y-3">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-100">
                    Địa Chỉ Nhận Hàng
                </h3>
                <div>
                    <strong class="text-zinc-950 font-bold block">{{ $order->customer_name }}</strong>
                    <p class="text-zinc-600">{{ $order->customer_phone }}</p>
                    <p class="text-zinc-600">{{ $order->customer_email }}</p>
                </div>
                <div class="pt-2 border-t border-zinc-100 text-zinc-700">
                    <p>{{ $order->shipping_address }}</p>
                    @if($order->province)
                        <p>{{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</p>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
