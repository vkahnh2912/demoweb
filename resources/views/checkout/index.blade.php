@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12"
     x-data="{
        paymentMethod: 'cod'
     }">

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Giỏ Hàng', 'url' => route('cart.index')],
        ['label' => 'Thanh Toán']
    ]" />

    <div class="py-4 border-b border-zinc-200 mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
            Thanh Toán Đơn Hàng
        </h1>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- LEFT: SHIPPING INFO & PAYMENT METHOD (7 COLS) -->
            <div class="lg:col-span-7 space-y-8">

                <!-- 1. Customer & Shipping Address -->
                <div class="space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 flex items-center gap-2">
                        <span class="w-5 h-5 bg-zinc-950 text-white rounded-full inline-flex items-center justify-center text-[10px]">1</span>
                        <span>Thông Tin Giao Hàng</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block font-semibold text-zinc-700">Họ và tên người nhận *</label>
                            <input type="text"
                                   name="customer_name"
                                   required
                                   value="{{ old('customer_name', $defaultAddress?->full_name ?? $user?->name) }}"
                                   placeholder="Nguyễn Văn A"
                                   class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                            @error('customer_name') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block font-semibold text-zinc-700">Số điện thoại *</label>
                            <input type="tel"
                                   name="customer_phone"
                                   required
                                   value="{{ old('customer_phone', $defaultAddress?->phone ?? $user?->phone) }}"
                                   placeholder="0912 345 678"
                                   class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                            @error('customer_phone') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block font-semibold text-zinc-700">Email nhận thông báo *</label>
                            <input type="email"
                                   name="customer_email"
                                   required
                                   value="{{ old('customer_email', $user?->email) }}"
                                   placeholder="email@example.com"
                                   class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                            @error('customer_email') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <label class="block font-semibold text-zinc-700">Địa chỉ cụ thể (Số nhà, tên đường) *</label>
                            <input type="text"
                                   name="shipping_address"
                                   required
                                   value="{{ old('shipping_address', $defaultAddress?->address) }}"
                                   placeholder="Số 123 Đường Lê Lợi"
                                   class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                            @error('shipping_address') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-1">
                            <label class="block font-semibold text-zinc-700">Tỉnh / Thành phố</label>
                            <input type="text"
                                   name="province"
                                   value="{{ old('province', $defaultAddress?->province ?? 'TP. Hồ Chí Minh') }}"
                                   placeholder="TP. Hồ Chí Minh"
                                   class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                        </div>

                        <div class="space-y-1">
                            <label class="block font-semibold text-zinc-700">Quận / Huyện</label>
                            <input type="text"
                                   name="district"
                                   value="{{ old('district', $defaultAddress?->district ?? 'Quận 1') }}"
                                   placeholder="Quận 1"
                                   class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                        </div>

                        <div class="space-y-1 sm:col-span-2">
                            <label class="block font-semibold text-zinc-700">Ghi chú giao hàng (Tùy chọn)</label>
                            <textarea name="note"
                                      rows="2"
                                      placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi đến..."
                                      class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('note') }}</textarea>
                        </div>

                        @auth
                            <div class="sm:col-span-2 pt-1">
                                <label class="flex items-center gap-2 text-xs text-zinc-700 cursor-pointer">
                                    <input type="checkbox" name="save_address" value="1" class="w-4 h-4 text-zinc-950 border-zinc-300 focus:ring-0 rounded-none" />
                                    <span>Lưu địa chỉ này vào sổ địa chỉ tài khoản của tôi</span>
                                </label>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- 2. Payment Method -->
                <div class="space-y-4 pt-6 border-t border-zinc-200">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 flex items-center gap-2">
                        <span class="w-5 h-5 bg-zinc-950 text-white rounded-full inline-flex items-center justify-center text-[10px]">2</span>
                        <span>Phương Thức Thanh Toán</span>
                    </h2>

                    <div class="space-y-3 text-xs">
                        <!-- COD Option -->
                        <label class="flex items-start gap-3 p-4 border transition-colors cursor-pointer"
                               :class="paymentMethod === 'cod' ? 'border-zinc-950 bg-zinc-50' : 'border-zinc-200 hover:border-zinc-400'">
                            <input type="radio"
                                   name="payment_method"
                                   value="cod"
                                   x-model="paymentMethod"
                                   class="mt-0.5 text-zinc-950 focus:ring-0" />
                            <div class="space-y-1">
                                <span class="font-bold text-zinc-950 block">Thanh toán khi nhận hàng (COD)</span>
                                <span class="text-zinc-500 block">Bạn chỉ phải thanh toán tiền mặt sau khi nhận và kiểm tra hàng hóa nguyên vẹn.</span>
                            </div>
                        </label>

                        <!-- Bank Transfer Option -->
                        <label class="flex items-start gap-3 p-4 border transition-colors cursor-pointer"
                               :class="paymentMethod === 'bank_transfer' ? 'border-zinc-950 bg-zinc-50' : 'border-zinc-200 hover:border-zinc-400'">
                            <input type="radio"
                                   name="payment_method"
                                   value="bank_transfer"
                                   x-model="paymentMethod"
                                   class="mt-0.5 text-zinc-950 focus:ring-0" />
                            <div class="space-y-1">
                                <span class="font-bold text-zinc-950 block">Chuyển khoản ngân hàng (VietQR)</span>
                                <span class="text-zinc-500 block">Thông tin tài khoản và mã QR chuyển khoản sẽ hiển thị ngay sau khi bạn đặt hàng thành công.</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- RIGHT: ORDER SUMMARY (5 COLS) -->
            <div class="lg:col-span-5">
                <div class="bg-zinc-50 p-6 border border-zinc-200 space-y-6 sticky top-28">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-3 border-b border-zinc-200">
                        Đơn Hàng Của Bạn ({{ $items->sum('quantity') }})
                    </h2>

                    <!-- Items list -->
                    <div class="max-h-72 overflow-y-auto divide-y divide-zinc-200 pr-1">
                        @foreach($items as $item)
                            <div class="py-3 flex gap-3 text-xs">
                                <div class="relative flex-shrink-0">
                                    <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-14 h-18 object-cover bg-zinc-100" />
                                    <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-zinc-900 text-white rounded-full flex items-center justify-center text-[10px] font-bold">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <h4 class="font-bold text-zinc-900 line-clamp-1">{{ $item->product->name }}</h4>
                                        @if($item->options && (isset($item->options['size']) || isset($item->options['color'])))
                                            <p class="text-[11px] text-zinc-500 mt-0.5">
                                                {{ isset($item->options['size']) ? 'Size: ' . $item->options['size'] : '' }}
                                                {{ isset($item->options['color']) ? ' | Màu: ' . $item->options['color'] : '' }}
                                            </p>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-zinc-900">{{ number_format($item->subtotal) }}₫</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals breakdown -->
                    <div class="space-y-2.5 text-xs pt-4 border-t border-zinc-200">
                        <div class="flex justify-between text-zinc-600">
                            <span>Tạm tính</span>
                            <span class="font-semibold text-zinc-900">{{ number_format($totals['subtotal']) }}₫</span>
                        </div>

                        @if($totals['discount'] > 0)
                            <div class="flex justify-between text-rose-600">
                                <span>Giảm giá ({{ $totals['coupon']?->code }})</span>
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

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-4 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-bold uppercase tracking-widest transition-colors shadow-xl">
                        Đặt Hàng Ngay
                    </button>

                    <p class="text-[11px] text-zinc-400 text-center leading-relaxed">
                        Bằng việc bấm đặt hàng, bạn đồng ý với các <a href="{{ route('terms') }}" class="underline hover:text-zinc-600">Điều khoản dịch vụ</a> và <a href="{{ route('privacy') }}" class="underline hover:text-zinc-600">Chính sách bảo mật</a> của NOVAWEAR.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
