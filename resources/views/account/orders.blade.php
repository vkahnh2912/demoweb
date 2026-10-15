@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Tài Khoản', 'url' => route('account.dashboard')],
        ['label' => 'Lịch Sử Đơn Hàng']
    ]" />

    <div class="py-4 border-b border-zinc-200 mb-8 flex items-center justify-between">
        <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
            Lịch Sử Đơn Hàng
        </h1>
        <a href="{{ route('account.dashboard') }}" class="text-xs font-semibold text-zinc-600 hover:text-black">
            ← Quay lại trang tổng quan
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="py-16 text-center space-y-3">
            <p class="text-sm font-semibold text-zinc-800">Bạn chưa có đơn hàng nào.</p>
            <a href="{{ route('shop') }}" class="inline-block px-6 py-2.5 bg-zinc-950 text-white text-xs uppercase tracking-wider font-semibold">
                Khám Phá Cửa Hàng
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="border border-zinc-200 bg-white p-5 sm:p-6 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-zinc-100 gap-2 text-xs">
                        <div>
                            <span class="font-bold text-zinc-950 text-sm">Đơn hàng #{{ $order->order_number }}</span>
                            <span class="text-zinc-400 ml-2">Đặt ngày {{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-wider
                                {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                {{ $order->status === 'pending' ? 'bg-amber-50 text-amber-700' : '' }}
                                {{ in_array($order->status, ['confirmed', 'processing', 'shipping']) ? 'bg-sky-50 text-sky-700' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : '' }}
                            ">
                                {{ $order->status_label }}
                            </span>
                            <a href="{{ route('account.orders.detail', $order->order_number) }}" class="px-3 py-1.5 bg-zinc-950 text-white text-xs uppercase font-semibold tracking-wider hover:bg-zinc-800 transition-colors">
                                Chi Tiết
                            </a>
                        </div>
                    </div>

                    <!-- Items list preview -->
                    <div class="space-y-2">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between text-xs py-1">
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-zinc-900">{{ $item->product_name }}</span>
                                    <span class="text-zinc-500">x{{ $item->quantity }}</span>
                                    @if($item->options && (isset($item->options['size']) || isset($item->options['color'])))
                                        <span class="text-zinc-400">({{ $item->options['size'] ?? '' }} {{ $item->options['color'] ?? '' }})</span>
                                    @endif
                                </div>
                                <span class="font-medium text-zinc-900">{{ number_format($item->total) }}₫</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-3 border-t border-zinc-100 flex items-center justify-between text-xs font-semibold text-zinc-950">
                        <span>Phương thức: {{ $order->payment_method_label }}</span>
                        <span>Tổng tiền: <strong class="text-sm">{{ number_format($order->total) }}₫</strong></span>
                    </div>
                </div>
            @endforeach

            <div>
                <x-pagination :paginator="$orders" />
            </div>
        </div>
    @endif
</div>
@endsection
