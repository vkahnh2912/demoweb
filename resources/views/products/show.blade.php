@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10"
     x-data="{
        selectedImage: '{{ $product->primary_image_url }}',
        selectedSize: '{{ $product->variants->first()?->size ?? 'M' }}',
        selectedColor: '{{ $product->variants->first()?->color ?? 'Đen' }}',
        quantity: 1,
        addingToCart: false,
        buyingNow: false,
        inWishlist: false,

        addToCart(redirectCheckout = false) {
            this.addingToCart = true;
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    quantity: this.quantity,
                    options: {
                        size: this.selectedSize,
                        color: this.selectedColor
                    }
                })
            })
            .then(res => res.json())
            .then(data => {
                this.addingToCart = false;
                if (data.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                    if (redirectCheckout) {
                        window.location.href = '{{ route('checkout.index') }}';
                    } else {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message, type: 'success' } }));
                        window.dispatchEvent(new CustomEvent('open-cart-drawer'));
                    }
                }
            })
            .catch(() => this.addingToCart = false);
        },

        toggleWishlist() {
            fetch('{{ route('wishlist.toggle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: {{ $product->id }} })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'unauthenticated') {
                    window.location.href = '{{ route('login') }}';
                    return;
                }
                this.inWishlist = data.in_wishlist;
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message, type: 'success' } }));
                window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: { count: data.count } }));
            });
        }
     }">

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Cửa Hàng', 'url' => route('shop')],
        ['label' => $product->category?->name ?? 'Sản Phẩm', 'url' => $product->category ? route('category.show', $product->category->slug) : route('shop')],
        ['label' => $product->name]
    ]" />

    <!-- TOP SECTION: GALLERY + DETAILS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 pt-4 pb-16">

        <!-- LEFT: GALLERY (7 COLS) -->
        <div class="lg:col-span-7 flex flex-col-reverse sm:flex-row gap-4">
            <!-- Thumbnails List -->
            @if($product->images->count() > 1)
                <div class="flex sm:flex-col gap-3 overflow-x-auto sm:overflow-y-auto sm:w-20 flex-shrink-0 no-scrollbar">
                    @foreach($product->images as $img)
                        <button type="button"
                                @click="selectedImage = '{{ $img->url }}'"
                                class="relative w-16 sm:w-20 aspect-[3/4] flex-shrink-0 bg-zinc-100 border-2 overflow-hidden transition-all duration-200"
                                :class="selectedImage === '{{ $img->url }}' ? 'border-zinc-950 opacity-100' : 'border-transparent opacity-60 hover:opacity-100'">
                            <img src="{{ $img->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-center" />
                        </button>
                    @endforeach
                </div>
            @endif

            <!-- Main Big Image -->
            <div class="flex-1 relative aspect-[3/4] bg-zinc-100 overflow-hidden">
                <img :src="selectedImage"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover object-center transition-all duration-300" />

                <!-- Badges -->
                <div class="absolute top-4 left-4 flex flex-col gap-1.5 z-10">
                    @if($product->discount_percent > 0)
                        <span class="px-2.5 py-1 bg-rose-600 text-white text-xs font-bold uppercase tracking-wider">
                            -{{ $product->discount_percent }}%
                        </span>
                    @endif
                    @if($product->new_arrival)
                        <span class="px-2.5 py-1 bg-zinc-950 text-white text-xs font-bold uppercase tracking-wider">
                            MỚI
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- RIGHT: PRODUCT BUY BOX (5 COLS) -->
        <div class="lg:col-span-5 flex flex-col justify-start space-y-6">

            <!-- Category & Brand -->
            <div class="flex items-center justify-between text-xs text-zinc-500 uppercase tracking-wider">
                <span>{{ $product->category?->name ?? 'NOVAWEAR' }}</span>
                <span>SKU: <strong class="text-zinc-900">{{ $product->sku }}</strong></span>
            </div>

            <!-- Product Title -->
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950 leading-tight">
                {{ $product->name }}
            </h1>

            <!-- Rating & Reviews Count -->
            <div class="flex items-center gap-3 text-xs">
                <x-rating :rating="$ratingAvg" :count="$ratingCount" :showNumber="true" />
                <span class="text-zinc-300">|</span>
                <span class="text-emerald-700 font-medium">
                    {{ $product->is_in_stock ? 'Còn hàng (' . $product->stock . ' cái)' : 'Tạm hết hàng' }}
                </span>
            </div>

            <!-- Price -->
            <div class="py-2 border-y border-zinc-100 flex items-baseline gap-3">
                <x-price :price="$product->price" :salePrice="$product->sale_price" size="xl" />
                @if($product->discount_percent > 0)
                    <span class="text-xs font-semibold text-rose-600">
                        Tiết kiệm {{ number_format($product->price - $product->sale_price) }}₫
                    </span>
                @endif
            </div>

            <!-- Short description -->
            @if($product->short_description)
                <p class="text-xs text-zinc-600 leading-relaxed font-light">
                    {{ $product->short_description }}
                </p>
            @endif

            <!-- Variant Selector: Color -->
            @if($variantsByColor->isNotEmpty())
                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold uppercase tracking-wider text-zinc-900">Màu Sắc:</span>
                        <span class="text-zinc-500" x-text="selectedColor"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        @foreach($variantsByColor->keys() as $color)
                            <button type="button"
                                    @click="selectedColor = '{{ $color }}'"
                                    class="px-4 py-2 border text-xs font-medium uppercase transition-colors"
                                    :class="selectedColor === '{{ $color }}' ? 'border-zinc-950 bg-zinc-950 text-white' : 'border-zinc-200 text-zinc-800 hover:border-zinc-500'">
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Variant Selector: Size -->
            @if($variantsBySize->isNotEmpty())
                <div class="space-y-2">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold uppercase tracking-wider text-zinc-900">Kích Cỡ:</span>
                        <span class="text-zinc-500" x-text="selectedSize"></span>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($variantsBySize->keys() as $size)
                            <button type="button"
                                    @click="selectedSize = '{{ $size }}'"
                                    class="py-2.5 border text-xs font-bold uppercase transition-colors text-center"
                                    :class="selectedSize === '{{ $size }}' ? 'border-zinc-950 bg-zinc-950 text-white' : 'border-zinc-200 text-zinc-800 hover:border-zinc-500'">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quantity & Actions -->
            <div class="space-y-3 pt-2">
                <div class="flex items-center gap-3">
                    <!-- Quantity Input -->
                    <div class="inline-flex items-center border border-zinc-300 h-12">
                        <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-full flex items-center justify-center text-zinc-600 hover:text-black text-sm">-</button>
                        <input type="number" x-model.number="quantity" min="1" max="{{ $product->stock }}" class="w-12 text-center border-none text-xs font-bold focus:ring-0 text-zinc-900 p-0" />
                        <button type="button" @click="if(quantity < {{ $product->stock }}) quantity++" class="w-10 h-full flex items-center justify-center text-zinc-600 hover:text-black text-sm">+</button>
                    </div>

                    <!-- Add to Cart -->
                    <button type="button"
                            @click="addToCart(false)"
                            :disabled="!{{ $product->is_in_stock ? 'true' : 'false' }} || addingToCart"
                            class="flex-1 h-12 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-bold uppercase tracking-widest transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="addingToCart">
                            <span class="inline-block animate-spin mr-1">⟳</span>
                        </template>
                        <span>Thêm Vào Giỏ Hàng</span>
                    </button>

                    <!-- Wishlist Button -->
                    <button type="button"
                            @click="toggleWishlist()"
                            class="w-12 h-12 border border-zinc-300 hover:border-zinc-900 flex items-center justify-center text-zinc-700 hover:text-rose-600 transition-colors"
                            aria-label="Thêm vào yêu thích">
                        <svg class="w-5 h-5 transition-colors" :class="{ 'fill-rose-600 text-rose-600': inWishlist }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Buy Now Button -->
                @if($product->is_in_stock)
                    <button type="button"
                            @click="addToCart(true)"
                            class="w-full h-12 bg-zinc-100 hover:bg-zinc-200 border border-zinc-300 text-zinc-900 text-xs font-bold uppercase tracking-widest transition-colors">
                        Mua Ngay
                    </button>
                @endif
            </div>

            <!-- Guarantee Perks -->
            <div class="pt-4 space-y-2 border-t border-zinc-100 text-[11px] text-zinc-500">
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Giao hàng tiêu chuẩn 2-3 ngày, miễn phí cho đơn từ 500.000₫</span>
                </p>
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Kiểm tra hàng trước khi thanh toán tận nơi COD</span>
                </p>
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Đổi trả miễn phí trong 7 ngày nếu không vừa size</span>
                </p>
            </div>
        </div>
    </div>

    <!-- LOWER SECTION: TABS (DESCRIPTION, SPECS, REVIEWS) -->
    <div class="border-t border-zinc-200 pt-12 pb-16" x-data="{ activeTab: 'description' }">
        <!-- Tab Headers -->
        <div class="flex items-center justify-center space-x-8 border-b border-zinc-200 pb-4 text-xs font-bold uppercase tracking-widest">
            <button type="button"
                    @click="activeTab = 'description'"
                    class="py-2 transition-colors relative"
                    :class="activeTab === 'description' ? 'text-zinc-950 border-b-2 border-zinc-950' : 'text-zinc-400 hover:text-zinc-700'">
                Mô Tả Chi Tiết
            </button>
            <button type="button"
                    @click="activeTab = 'specifications'"
                    class="py-2 transition-colors relative"
                    :class="activeTab === 'specifications' ? 'text-zinc-950 border-b-2 border-zinc-950' : 'text-zinc-400 hover:text-zinc-700'">
                Thông Số & Bảo Quản
            </button>
            <button type="button"
                    @click="activeTab = 'reviews'"
                    class="py-2 transition-colors relative"
                    :class="activeTab === 'reviews' ? 'text-zinc-950 border-b-2 border-zinc-950' : 'text-zinc-400 hover:text-zinc-700'">
                Đánh Giá Khách Hàng ({{ $ratingCount }})
            </button>
        </div>

        <!-- Tab 1: Description -->
        <div x-show="activeTab === 'description'" class="max-w-3xl mx-auto py-8 text-xs sm:text-sm text-zinc-600 leading-relaxed space-y-4">
            <p>{{ $product->description }}</p>
            <p>
                Sản phẩm được hoàn thiện bằng quy trình may kép gia cố các đường chịu lực, đảm bảo độ bền vượt trội qua nhiều lần giặt. Chất liệu có khả năng thoát ẩm tự nhiên, giảm nhăn và dễ dàng là ủi ở nhiệt độ trung bình.
            </p>
        </div>

        <!-- Tab 2: Specifications -->
        <div x-show="activeTab === 'specifications'" class="max-w-2xl mx-auto py-8 text-xs" style="display: none;">
            <dl class="divide-y divide-zinc-100">
                <div class="py-3 flex justify-between">
                    <dt class="text-zinc-500 font-medium">Chất liệu</dt>
                    <dd class="text-zinc-900 font-semibold">100% Cotton tự nhiên tuyển chọn / Organic Linen</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-zinc-500 font-medium">Phom dáng</dt>
                    <dd class="text-zinc-900 font-semibold">Relaxed Fit / Dáng suông hiện đại tôn dáng</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-zinc-500 font-medium">Thương hiệu</dt>
                    <dd class="text-zinc-900 font-semibold">{{ $product->brand?->name ?? 'NOVAWEAR Studio' }}</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-zinc-500 font-medium">Xuất xứ</dt>
                    <dd class="text-zinc-900 font-semibold">Sản xuất tại Việt Nam (Designed by NOVAWEAR)</dd>
                </div>
                <div class="py-3 flex justify-between">
                    <dt class="text-zinc-500 font-medium">Hướng dẫn giặt ủi</dt>
                    <dd class="text-zinc-900 font-semibold text-right max-w-xs">Giặt máy ở nhiệt độ dưới 30°C, không dùng thuốc tẩy chứa clo, ủi ở nhiệt độ trung bình.</dd>
                </div>
            </dl>
        </div>

        <!-- Tab 3: Reviews -->
        <div x-show="activeTab === 'reviews'" class="max-w-3xl mx-auto py-8 space-y-8" style="display: none;">
            <!-- Review Summary -->
            <div class="bg-zinc-50 p-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                <div>
                    <div class="text-4xl font-extrabold text-zinc-900">{{ number_format($ratingAvg, 1) }} / 5.0</div>
                    <div class="mt-1">
                        <x-rating :rating="$ratingAvg" />
                    </div>
                    <p class="text-xs text-zinc-500 mt-1">Dựa trên {{ $ratingCount }} đánh giá từ khách hàng đã mua</p>
                </div>

                @if($canReview)
                    <a href="#review-form" class="px-6 py-2.5 bg-zinc-900 text-white text-xs uppercase tracking-wider font-semibold hover:bg-zinc-800 transition-colors">
                        Viết Đánh Giá
                    </a>
                @endif
            </div>

            <!-- Review Items List -->
            <div class="divide-y divide-zinc-100">
                @forelse($product->reviews as $rev)
                    <div class="py-4 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-zinc-900">{{ $rev->user?->name ?? 'Khách hàng' }}</span>
                            <span class="text-zinc-400">{{ $rev->created_at->format('d/m/Y') }}</span>
                        </div>
                        <x-rating :rating="$rev->rating" />
                        <p class="text-xs text-zinc-600 leading-relaxed">{{ $rev->comment }}</p>
                    </div>
                @empty
                    <p class="text-xs text-zinc-500 text-center py-6">Chưa có đánh giá nào cho sản phẩm này.</p>
                @endforelse
            </div>

            <!-- Review submission form -->
            @if($canReview)
                <div id="review-form" class="pt-6 border-t border-zinc-200">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-zinc-900 mb-4">Gửi Đánh Giá Của Bạn</h4>
                    <form action="{{ route('product.review.store', $product->slug) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Mức độ hài lòng (Số sao):</label>
                            <select name="rating" class="border-zinc-300 text-xs py-2 px-3 focus:ring-0 focus:border-zinc-900 bg-white font-medium">
                                <option value="5">★★★★★ - Tuyệt vời (5/5)</option>
                                <option value="4">★★★★☆ - Rất tốt (4/5)</option>
                                <option value="3">★★★☆☆ - Bình thường (3/5)</option>
                                <option value="2">★★☆☆☆ - Chưa hài lòng (2/5)</option>
                                <option value="1">★☆☆☆☆ - Kém (1/5)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-zinc-700 mb-1">Nhận xét của bạn:</label>
                            <textarea name="comment" rows="4" required placeholder="Chia sẻ cảm nhận về chất vải, phom dáng và trải nghiệm mua hàng..." class="w-full text-xs p-3 border-zinc-300 focus:ring-0 focus:border-zinc-900"></textarea>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-zinc-950 text-white text-xs uppercase tracking-wider font-semibold hover:bg-zinc-800 transition-colors">
                            Gửi Đánh Giá
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- RELATED PRODUCTS -->
    @if($relatedProducts->isNotEmpty())
        <section class="border-t border-zinc-200 pt-16">
            <div class="text-center max-w-xl mx-auto mb-10 space-y-2">
                <span class="text-[11px] font-bold uppercase tracking-[0.25em] text-zinc-400">Gợi Ý Cho Bạn</span>
                <h2 class="text-2xl font-extrabold uppercase tracking-tight text-zinc-950">Sản Phẩm Tương Tự</h2>
            </div>
            <x-product-grid :products="$relatedProducts" :cols="4" />
        </section>
    @endif

    <!-- STICKY MOBILE PURCHASE BAR -->
    <div class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-zinc-200 px-3.5 py-2.5 lg:hidden flex items-center justify-between gap-3 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] pb-[calc(0.6rem+env(safe-area-inset-bottom))]">
        <div class="flex items-center gap-2.5 min-w-0 flex-1">
            <img :src="selectedImage" alt="{{ $product->name }}" class="w-10 h-12 object-cover bg-zinc-100 border border-zinc-200 shrink-0" />
            <div class="min-w-0">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-sm font-extrabold text-zinc-950">{{ number_format($product->sale_price ?? $product->price) }}₫</span>
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="text-[10px] text-zinc-400 line-through">{{ number_format($product->price) }}₫</span>
                    @endif
                </div>
                <p class="text-[11px] text-zinc-500 truncate">
                    <span x-text="selectedColor ? selectedColor : ''"></span>
                    <span x-show="selectedColor && selectedSize"> • </span>
                    <span x-text="selectedSize ? 'Size ' + selectedSize : ''"></span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <!-- Add to Cart Button -->
            <button type="button"
                    @click="addToCart(false)"
                    :disabled="!{{ $product->is_in_stock ? 'true' : 'false' }} || addingToCart"
                    class="px-3.5 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-[11px] font-bold uppercase tracking-wider disabled:opacity-50 transition-colors flex items-center gap-1.5 shadow-sm">
                <template x-if="addingToCart">
                    <span class="inline-block animate-spin mr-0.5">⟳</span>
                </template>
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span>Thêm Giỏ</span>
            </button>

            <!-- Buy Now Button -->
            @if($product->is_in_stock)
                <button type="button"
                        @click="addToCart(true)"
                        class="px-3.5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-[11px] font-bold uppercase tracking-wider transition-colors shadow-sm">
                    Mua Ngay
                </button>
            @endif
        </div>
    </div>

</div>
@endsection
