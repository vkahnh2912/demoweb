@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
     x-data="{
        loading: false,
        updateQuantity(itemId, qty) {
            this.loading = true;
            fetch('{{ route('cart.update') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cart_item_id: itemId, quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                window.location.reload();
            })
            .catch(() => this.loading = false);
        },
        removeItem(itemId) {
            if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
            this.loading = true;
            fetch('{{ route('cart.remove') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cart_item_id: itemId })
            })
            .then(res => res.json())
            .then(data => {
                window.location.reload();
            })
            .catch(() => this.loading = false);
        }
     }">

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Giỏ Hàng']]" />

    <div class="py-4 border-b border-zinc-200 mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
            Giỏ Hàng Của Bạn ({{ $items->sum('quantity') }})
        </h1>
    </div>

    @if($items->isEmpty())
        <div class="py-20 text-center space-y-4">
            <div class="w-20 h-20 bg-zinc-100 rounded-full flex items-center justify-center text-zinc-400 mx-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-zinc-900">Giỏ hàng của bạn đang trống</h2>
            <p class="text-xs text-zinc-500 max-w-sm mx-auto">Không có sản phẩm nào trong giỏ hàng. Hãy lướt xem các mẫu thiết kế mới nhất của chúng tôi!</p>
            <div class="pt-2">
                <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-8 py-3 bg-zinc-950 text-white text-xs font-bold uppercase tracking-wider hover:bg-zinc-800 transition-colors">
                    Tiếp Tục Mua Sắm
                </a>
            </div>
        </div>
    @else
        <!-- Free Shipping Progress -->
        <div class="bg-zinc-50 p-4 border border-zinc-200 mb-8 text-xs">
            @if($totals['needed_for_free_shipping'] > 0)
                <p class="text-zinc-700">
                    Mua thêm <strong class="text-zinc-950">{{ number_format($totals['needed_for_free_shipping']) }}₫</strong> để nhận ưu đãi <strong class="text-emerald-600">MIỄN PHÍ VẬN CHUYỂN</strong>!
                </p>
                <div class="w-full bg-zinc-200 h-2 mt-2 rounded-full overflow-hidden">
                    <div class="bg-zinc-950 h-full transition-all duration-300" style="width: {{ min(100, round(($totals['subtotal'] / $totals['free_shipping_threshold']) * 100)) }}%"></div>
                </div>
            @else
                <p class="text-emerald-700 font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Đơn hàng của bạn đã đủ điều kiện được <strong>MIỄN PHÍ GIAO HÀNG</strong>!
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- CART ITEMS TABLE (8 COLS) -->
            <div class="lg:col-span-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-200 text-zinc-400 uppercase tracking-wider font-semibold text-[11px] pb-3">
                                <th class="pb-3 font-semibold">Sản phẩm</th>
                                <th class="pb-3 font-semibold hidden sm:table-cell">Đơn giá</th>
                                <th class="pb-3 font-semibold text-center">Số lượng</th>
                                <th class="pb-3 font-semibold text-right">Tổng tiền</th>
                                <th class="pb-3 font-semibold text-right"><span class="sr-only">Xóa</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @foreach($items as $item)
                                <tr class="py-4">
                                    <!-- Product Info -->
                                    <td class="py-4 pr-4">
                                        <div class="flex items-center gap-4">
                                            <a href="{{ route('product.show', $item->product->slug) }}" class="flex-shrink-0">
                                                <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-16 h-20 object-cover bg-zinc-100" />
                                            </a>
                                            <div class="space-y-1">
                                                <h3 class="font-bold text-zinc-900 text-xs sm:text-sm hover:underline">
                                                    <a href="{{ route('product.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                                </h3>
                                                @if($item->options && (isset($item->options['size']) || isset($item->options['color'])))
                                                    <p class="text-[11px] text-zinc-500">
                                                        {{ isset($item->options['size']) ? 'Size: ' . $item->options['size'] : '' }}
                                                        {{ isset($item->options['color']) ? ' | Màu: ' . $item->options['color'] : '' }}
                                                    </p>
                                                @endif
                                                <p class="text-zinc-500 text-[11px] sm:hidden">
                                                    {{ number_format($item->price) }}₫
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Price -->
                                    <td class="py-4 hidden sm:table-cell font-medium text-zinc-900">
                                        {{ number_format($item->price) }}₫
                                    </td>

                                    <!-- Quantity Selector -->
                                    <td class="py-4 text-center">
                                        <div class="inline-flex items-center border border-zinc-200">
                                            <button type="button" @click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="w-7 h-7 flex items-center justify-center text-zinc-500 hover:text-black">-</button>
                                            <span class="w-8 text-center text-xs font-semibold text-zinc-900">{{ $item->quantity }}</span>
                                            <button type="button" @click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" class="w-7 h-7 flex items-center justify-center text-zinc-500 hover:text-black">+</button>
                                        </div>
                                    </td>

                                    <!-- Line Total -->
                                    <td class="py-4 text-right font-bold text-zinc-950 text-xs sm:text-sm">
                                        {{ number_format($item->subtotal) }}₫
                                    </td>

                                    <!-- Remove Button -->
                                    <td class="py-4 text-right pl-3">
                                        <button type="button" @click="removeItem({{ $item->id }})" class="text-zinc-400 hover:text-rose-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-6 flex justify-between items-center text-xs">
                    <a href="{{ route('shop') }}" class="inline-flex items-center gap-1.5 text-zinc-900 font-semibold hover:underline">
                        <span>← Tiếp tục mua sắm</span>
                    </a>
                </div>
            </div>

            <!-- ORDER SUMMARY (4 COLS) -->
            <div class="lg:col-span-4">
                <div class="bg-zinc-50 p-6 border border-zinc-200 space-y-6">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-200">
                        Tổng Đơn Hàng
                    </h2>

                    <!-- Summary lines -->
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-zinc-600">
                            <span>Tạm tính</span>
                            <span class="font-semibold text-zinc-900">{{ number_format($totals['subtotal']) }}₫</span>
                        </div>

                        @if($totals['discount'] > 0)
                            <div class="flex justify-between text-rose-600">
                                <span>Mã giảm giá ({{ $totals['coupon']?->code }})</span>
                                <span class="font-semibold">-{{ number_format($totals['discount']) }}₫</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-zinc-600">
                            <span>Phí vận chuyển</span>
                            <span class="font-semibold text-zinc-900">
                                {{ $totals['shipping_fee'] > 0 ? number_format($totals['shipping_fee']) . '₫' : 'Miễn phí' }}
                            </span>
                        </div>

                        <div class="flex justify-between text-base font-extrabold text-zinc-950 pt-3 border-t border-zinc-200">
                            <span>Tổng thanh toán</span>
                            <span>{{ number_format($totals['total']) }}₫</span>
                        </div>
                    </div>

                    <!-- Coupon Code Input Form -->
                    <div class="pt-3 border-t border-zinc-200">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-2">Mã Giảm Giá</label>
                        @if(session('applied_coupon'))
                            <div class="flex items-center justify-between p-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                                <span>Đang dùng: <strong>{{ session('applied_coupon') }}</strong></span>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:underline font-semibold ml-2">Gỡ bỏ</button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text"
                                       name="code"
                                       required
                                       placeholder="Ví dụ: WELCOME10"
                                       class="flex-1 text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 uppercase placeholder-zinc-400" />
                                <button type="submit" class="px-4 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs uppercase tracking-wider font-semibold transition-colors">
                                    Áp Dụng
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full py-3.5 bg-zinc-950 hover:bg-zinc-800 text-white text-center text-xs font-bold uppercase tracking-widest transition-colors shadow-lg">
                        Tiến Hành Thanh Toán
                    </a>

                    <div class="text-[11px] text-zinc-400 text-center space-y-1">
                        <p>Bảo mật thanh toán chuẩn SSL 256-bit</p>
                        <p>Kiểm tra hàng trước khi nhận (COD)</p>
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
