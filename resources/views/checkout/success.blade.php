@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="bg-white border border-zinc-200 p-6 sm:p-10 text-center space-y-6 shadow-sm">

        <!-- Checkmark Icon -->
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div class="space-y-2">
            <span class="text-xs uppercase font-bold tracking-widest text-emerald-600">Đặt Hàng Thành Công</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
                Cảm Ơn Bạn Đã Mua Hàng!
            </h1>
            <p class="text-xs text-zinc-500">
                Mã đơn hàng của bạn là: <strong class="text-zinc-950 text-sm font-mono tracking-wider">#{{ $order->order_number }}</strong>
            </p>
        </div>

        <!-- Bank Transfer Instructions (if bank_transfer) -->
        @if($order->payment_method === 'bank_transfer')
            <div class="bg-zinc-50 border border-zinc-200 p-6 text-left space-y-4 text-xs">
                <h3 class="font-bold uppercase tracking-wider text-zinc-950 border-b border-zinc-200 pb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Thông Tin Chuyển Khoản Ngân Hàng</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-zinc-700">
                    <div>
                        <span class="text-zinc-400 block text-[11px]">Ngân hàng:</span>
                        <strong class="text-zinc-900">MB BANK (Ngân hàng Quân Đội)</strong>
                    </div>
                    <div>
                        <span class="text-zinc-400 block text-[11px]">Số tài khoản:</span>
                        <strong class="text-zinc-900 font-mono text-sm">9999 8888 6666</strong>
                    </div>
                    <div>
                        <span class="text-zinc-400 block text-[11px]">Chủ tài khoản:</span>
                        <strong class="text-zinc-900">CONG TY TNHH NOVAWEAR VIETNAM</strong>
                    </div>
                    <div>
                        <span class="text-zinc-400 block text-[11px]">Số tiền cần chuyển:</span>
                        <strong class="text-rose-600 font-bold text-sm">{{ number_format($order->total) }}₫</strong>
                    </div>
                    <div class="sm:col-span-2 pt-2">
                        <span class="text-zinc-400 block text-[11px]">Nội dung chuyển khoản:</span>
                        <div class="p-2.5 bg-white border border-zinc-300 font-mono font-bold text-zinc-950 text-xs mt-1">
                            {{ $order->order_number }}
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-zinc-500 italic">
                    * Đơn hàng sẽ tự động chuyển sang trạng thái chuẩn bị hàng ngay khi hệ thống ghi nhận thanh toán thành công.
                </p>
            </div>
        @endif

        <!-- Order Summary Box -->
        <div class="bg-zinc-50 border border-zinc-100 p-6 text-left space-y-4 text-xs">
            <h3 class="font-bold uppercase tracking-wider text-zinc-950 border-b border-zinc-200 pb-2">
                Chi Tiết Đơn Hàng
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <span class="text-zinc-400 text-[11px] block">Người nhận:</span>
                    <strong class="text-zinc-900">{{ $order->customer_name }}</strong>
                    <p class="text-zinc-600">{{ $order->customer_phone }}</p>
                    <p class="text-zinc-600">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <span class="text-zinc-400 text-[11px] block">Địa chỉ giao hàng:</span>
                    <p class="text-zinc-900 font-medium">{{ $order->shipping_address }}</p>
                    @if($order->province)
                        <p class="text-zinc-600">{{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</p>
                    @endif
                </div>
            </div>

            <!-- Items -->
            <div class="divide-y divide-zinc-200 pt-2">
                @foreach($order->items as $item)
                    <div class="py-2.5 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="font-medium text-zinc-900">{{ $item->product_name }}</span>
                            <span class="text-zinc-400">x{{ $item->quantity }}</span>
                        </div>
                        <span class="font-semibold text-zinc-900">{{ number_format($item->total) }}₫</span>
                    </div>
                @endforeach
            </div>

            <div class="pt-3 border-t border-zinc-200 space-y-1.5 font-medium">
                <div class="flex justify-between text-zinc-600">
                    <span>Phí vận chuyển:</span>
                    <span>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee) . '₫' : 'Miễn phí' }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between text-rose-600">
                        <span>Giảm giá:</span>
                        <span>-{{ number_format($order->discount_amount) }}₫</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm font-bold text-zinc-950 pt-2 border-t border-zinc-200">
                    <span>Tổng thanh toán:</span>
                    <span>{{ number_format($order->total) }}₫</span>
                </div>
            </div>
        </div>

        <!-- Action Links -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-4">
            <a href="{{ route('home') }}" class="px-8 py-3 bg-zinc-950 text-white text-xs font-bold uppercase tracking-wider hover:bg-zinc-800 transition-colors">
                Quay Lại Trang Chủ
            </a>
            @auth
                <a href="{{ route('account.orders.detail', $order->order_number) }}" class="px-8 py-3 bg-white border border-zinc-900 text-zinc-900 text-xs font-bold uppercase tracking-wider hover:bg-zinc-50 transition-colors">
                    Xem Đơn Hàng Của Tôi
                </a>
            @endauth
        </div>

    </div>
</div>
@endsection
