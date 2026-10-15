<div x-data="{
        isOpen: false,
        items: [],
        totals: { subtotal: 0, shipping_fee: 0, discount: 0, total: 0, free_shipping_threshold: 500000, needed_for_free_shipping: 0 },
        count: 0,
        loading: false,
        loadCart() {
            this.loading = true;
            fetch('{{ route('cart.mini') }}')
                .then(res => res.json())
                .then(data => {
                    this.items = data.items;
                    this.totals = data.totals;
                    this.count = data.count;
                    this.loading = false;
                })
                .catch(() => this.loading = false);
        },
        updateQty(id, qty) {
            fetch('{{ route('cart.update') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cart_item_id: id, quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                this.loadCart();
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
            });
        },
        removeItem(id) {
            fetch('{{ route('cart.remove') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ cart_item_id: id })
            })
            .then(res => res.json())
            .then(data => {
                this.loadCart();
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
            });
        },
        formatMoney(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
        }
    }"
    @open-cart-drawer.window="isOpen = true; loadCart()"
    @keydown.escape.window="isOpen = false"
    class="relative z-50">

    <!-- Backdrop -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
         @click="isOpen = false"
         style="display: none;"></div>

    <!-- Drawer Panel -->
    <div x-show="isOpen"
         x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 max-w-full flex pl-10"
         style="display: none;">

        <div class="w-screen max-w-md bg-white shadow-2xl flex flex-col">
            <!-- Header -->
            <div class="p-5 border-b border-zinc-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900">Giỏ Hàng Của Bạn</h2>
                    <span class="px-2 py-0.5 text-xs bg-zinc-100 text-zinc-800 font-semibold" x-text="count + ' món'"></span>
                </div>
                <button type="button" @click="isOpen = false" class="text-zinc-400 hover:text-zinc-900 transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Free Shipping Bar -->
            <div class="bg-zinc-50 p-3.5 border-b border-zinc-200 text-xs">
                <template x-if="totals.needed_for_free_shipping > 0">
                    <div>
                        <p class="text-zinc-600">
                            Mua thêm <span class="font-bold text-zinc-900" x-text="formatMoney(totals.needed_for_free_shipping)"></span> để được <span class="font-bold text-emerald-600">MIỄN PHÍ VẬN CHUYỂN</span>!
                        </p>
                        <div class="w-full bg-zinc-200 h-1.5 mt-2 rounded-full overflow-hidden">
                            <div class="bg-zinc-900 h-full transition-all duration-300"
                                 :style="'width: ' + Math.min(100, Math.round((totals.subtotal / totals.free_shipping_threshold) * 100)) + '%'"></div>
                        </div>
                    </div>
                </template>
                <template x-if="totals.needed_for_free_shipping <= 0 && totals.subtotal > 0">
                    <p class="text-emerald-700 font-medium flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Đơn hàng của bạn đã đủ điều kiện được <strong>MIỄN PHÍ GIAO HÀNG</strong>!
                    </p>
                </template>
            </div>

            <!-- Cart Items List -->
            <div class="flex-1 overflow-y-auto p-5 divide-y divide-zinc-100">
                <template x-if="items.length === 0">
                    <div class="py-16 text-center">
                        <svg class="w-12 h-12 text-zinc-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="text-sm font-medium text-zinc-900">Giỏ hàng của bạn đang trống</p>
                        <p class="text-xs text-zinc-500 mt-1">Hãy khám phá bộ sưu tập và chọn món đồ yêu thích nhé.</p>
                        <button type="button" @click="isOpen = false; window.location.href = '{{ route('shop') }}'" class="mt-5 px-6 py-2.5 bg-zinc-900 text-white text-xs uppercase tracking-wider hover:bg-zinc-800 transition-colors">
                            Mua Sắm Ngay
                        </button>
                    </div>
                </template>

                <template x-for="item in items" :key="item.id">
                    <div class="py-4 flex gap-4">
                        <img :src="item.product.primary_image_url || 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=400&q=80'"
                             :alt="item.product.name"
                             class="w-16 h-20 object-cover bg-zinc-100 flex-shrink-0" />

                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="text-xs font-semibold text-zinc-900 line-clamp-1" x-text="item.product.name"></h4>
                                    <button type="button" @click="removeItem(item.id)" class="text-zinc-400 hover:text-rose-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                <template x-if="item.options && (item.options.size || item.options.color)">
                                    <p class="text-[11px] text-zinc-500 mt-0.5">
                                        <span x-text="item.options.size ? 'Size: ' + item.options.size : ''"></span>
                                        <span x-text="item.options.color ? ' | Màu: ' + item.options.color : ''"></span>
                                    </p>
                                </template>

                                <p class="text-xs font-semibold text-zinc-900 mt-1" x-text="formatMoney(item.price)"></p>
                            </div>

                            <!-- Qty adjuster -->
                            <div class="flex items-center gap-2 mt-2">
                                <div class="inline-flex items-center border border-zinc-200">
                                    <button type="button" @click="updateQty(item.id, item.quantity - 1)" class="w-6 h-6 flex items-center justify-center text-zinc-500 hover:text-zinc-900 text-xs">-</button>
                                    <span class="w-8 text-center text-xs font-medium text-zinc-900" x-text="item.quantity"></span>
                                    <button type="button" @click="updateQty(item.id, item.quantity + 1)" class="w-6 h-6 flex items-center justify-center text-zinc-500 hover:text-zinc-900 text-xs">+</button>
                                </div>
                                <span class="text-xs font-semibold text-zinc-800 ml-auto" x-text="formatMoney(item.price * item.quantity)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Footer & Checkout Button -->
            <template x-if="items.length > 0">
                <div class="p-5 border-t border-zinc-200 bg-zinc-50">
                    <div class="space-y-2 mb-4 text-xs">
                        <div class="flex justify-between text-zinc-600">
                            <span>Tạm tính</span>
                            <span class="font-medium text-zinc-900" x-text="formatMoney(totals.subtotal)"></span>
                        </div>
                        <template x-if="totals.discount > 0">
                            <div class="flex justify-between text-rose-600">
                                <span>Giảm giá</span>
                                <span class="font-medium" x-text="'-' + formatMoney(totals.discount)"></span>
                            </div>
                        </template>
                        <div class="flex justify-between text-zinc-600">
                            <span>Phí vận chuyển</span>
                            <span class="font-medium text-zinc-900" x-text="totals.shipping_fee > 0 ? formatMoney(totals.shipping_fee) : 'Miễn phí'"></span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-zinc-900 pt-2 border-t border-zinc-200">
                            <span>Tổng thanh toán</span>
                            <span x-text="formatMoney(totals.total)"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('cart.index') }}" class="w-full py-3 bg-white border border-zinc-900 text-zinc-900 text-center text-xs uppercase tracking-wider font-semibold hover:bg-zinc-100 transition-colors">
                            Xem Giỏ Hàng
                        </a>
                        <a href="{{ route('checkout.index') }}" class="w-full py-3 bg-zinc-950 text-white text-center text-xs uppercase tracking-wider font-semibold hover:bg-zinc-800 transition-colors">
                            Thanh Toán
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
