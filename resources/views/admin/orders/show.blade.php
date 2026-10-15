@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-zinc-200">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="font-semibold text-zinc-500 hover:text-black mb-1 inline-block">← Danh sách đơn hàng</a>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Chi Tiết Đơn Hàng #{{ $order->order_number }}</h1>
            <p class="text-zinc-500">Đặt lúc {{ $order->created_at->format('H:i d/m/Y') }} ({{ $order->created_at->diffForHumans() }})</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-zinc-100 font-bold uppercase tracking-wider text-[11px] border border-zinc-200">
                Trạng thái: {{ $order->status_label }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main: Products & Totals -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-zinc-200 shadow-sm p-5">
                <h2 class="font-bold uppercase tracking-wider text-zinc-800 pb-3 border-b border-zinc-100 mb-4">Sản Phẩm Đã Mua ({{ $order->items->count() }})</h2>
                <div class="divide-y divide-zinc-100">
                    @foreach($order->items as $item)
                        <div class="py-3 flex gap-4 items-center">
                            @if($item->product && $item->product->primary_image)
                                <img src="{{ asset('storage/' . $item->product->primary_image) }}" class="w-14 h-18 object-cover border border-zinc-200 flex-shrink-0" />
                            @else
                                <div class="w-14 h-18 bg-zinc-100 flex items-center justify-center text-zinc-400 text-[10px] flex-shrink-0">No IMG</div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <a href="{{ $item->product ? route('products.show', $item->product->slug) : '#' }}" target="_blank" class="font-semibold text-zinc-900 hover:underline block truncate">
                                    {{ $item->product_name }}
                                </a>
                                <div class="text-[11px] text-zinc-500 mt-0.5">
                                    @if($item->variant_size) Cỡ: <span class="font-medium text-zinc-700">{{ $item->variant_size }}</span> @endif
                                    @if($item->variant_color) | Màu: <span class="font-medium text-zinc-700">{{ $item->variant_color }}</span> @endif
                                </div>
                                <div class="text-zinc-500 mt-1">Đơn giá: {{ number_format($item->price) }}₫ × {{ $item->quantity }}</div>
                            </div>
                            <div class="text-right font-bold text-zinc-900">
                                {{ number_format($item->subtotal) }}₫
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 mt-4 border-t border-zinc-200 space-y-2">
                    <div class="flex justify-between text-zinc-600">
                        <span>Tạm tính</span>
                        <span class="font-semibold text-zinc-800">{{ number_format($order->subtotal) }}₫</span>
                    </div>
                    <div class="flex justify-between text-zinc-600">
                        <span>Phí vận chuyển</span>
                        <span class="font-semibold text-zinc-800">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee) . '₫' : 'Miễn phí' }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-emerald-700">
                            <span>Mã giảm giá {{ $order->coupon ? '('.$order->coupon->code.')' : '' }}</span>
                            <span class="font-bold">-{{ number_format($order->discount_amount) }}₫</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm font-bold text-zinc-950 pt-2 border-t border-zinc-200">
                        <span>Tổng tiền thu</span>
                        <span class="text-base">{{ number_format($order->total) }}₫</span>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Details -->
            <div class="bg-white border border-zinc-200 shadow-sm p-5 space-y-4">
                <h2 class="font-bold uppercase tracking-wider text-zinc-800 pb-3 border-b border-zinc-100">Thông Tin Giao Hàng</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <div class="text-zinc-400 text-[11px]">Họ tên người nhận</div>
                        <div class="font-bold text-zinc-900 text-sm mt-0.5">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-[11px]">Số điện thoại</div>
                        <div class="font-bold text-zinc-900 text-sm mt-0.5">{{ $order->customer_phone }}</div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-[11px]">Email</div>
                        <div class="text-zinc-800 mt-0.5">{{ $order->customer_email }}</div>
                    </div>
                    <div>
                        <div class="text-zinc-400 text-[11px]">Tài khoản khách hàng</div>
                        <div class="text-zinc-800 mt-0.5">
                            @if($order->user)
                                <a href="{{ route('admin.customers.show', $order->user) }}" class="text-sky-600 hover:underline font-semibold">
                                    {{ $order->user->name }} (#{{ $order->user->id }})
                                </a>
                            @else
                                <span class="italic text-zinc-400">Khách vãng lai (Guest)</span>
                            @endif
                        </div>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="text-zinc-400 text-[11px]">Địa chỉ nhận hàng</div>
                        <div class="font-medium text-zinc-900 mt-0.5">
                            {{ $order->shipping_address }}, {{ $order->shipping_ward }}, {{ $order->shipping_district }}, {{ $order->shipping_province }}
                        </div>
                    </div>
                    @if($order->note)
                        <div class="sm:col-span-2 p-3 bg-zinc-50 border border-zinc-200">
                            <div class="text-zinc-500 font-semibold mb-0.5">Ghi chú từ khách:</div>
                            <div class="text-zinc-800 italic">"{{ $order->note }}"</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar: Update Status Form & Payment Info -->
        <div class="space-y-6">
            <!-- Update Order Status Form -->
            <div class="bg-white border border-zinc-200 shadow-sm p-5 space-y-4">
                <h2 class="font-bold uppercase tracking-wider text-zinc-800 pb-3 border-b border-zinc-100">Cập Nhật Trạng Thái</h2>
                
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block font-semibold text-zinc-700 mb-1">Trạng thái đơn hàng</label>
                        <select name="status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white font-medium">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xử lý (Pending)</option>
                            <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận (Confirmed)</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang đóng gói (Processing)</option>
                            <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>Đang giao hàng (Shipping)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Đã hoàn tất (Completed)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy (Cancelled)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 mb-1">Trạng thái thanh toán</label>
                        <select name="payment_status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white font-medium">
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Chờ thanh toán (Pending)</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán (Paid)</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Thanh toán thất bại (Failed)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-zinc-950 hover:bg-black text-white font-bold uppercase tracking-wider text-xs">
                        Lưu Cập Nhật
                    </button>
                </form>
            </div>

            <!-- Payment Info Box -->
            <div class="bg-white border border-zinc-200 shadow-sm p-5 space-y-3">
                <h2 class="font-bold uppercase tracking-wider text-zinc-800 pb-3 border-b border-zinc-100">Phương Thức Thanh Toán</h2>
                <div>
                    <div class="text-zinc-500 font-semibold uppercase text-[11px]">{{ $order->payment_method === 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng (Bank Transfer)' }}</div>
                    <div class="mt-2 text-[11px] text-zinc-600">
                        @if($order->payment_method === 'bank_transfer')
                            Khách chuyển khoản trực tiếp qua ngân hàng. Vui lòng đối soát sao kê trước khi chuyển sang "Đã thanh toán".
                        @else
                            Thu tiền mặt {{ number_format($order->total) }}₫ từ khách khi shipper giao hàng thành công.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
