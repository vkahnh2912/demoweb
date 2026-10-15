@props([
    'product',
])

@php
    $hasSale = $product->sale_price !== null && (float)$product->sale_price > 0 && (float)$product->sale_price < (float)$product->price;
    $isNew = $product->new_arrival;
@endphp

<div class="group relative flex flex-col bg-white overflow-hidden transition-all duration-300"
     x-data="{
        inWishlist: false,
        loadingWishlist: false,
        toggleWishlist(id) {
            this.loadingWishlist = true;
            fetch('{{ route('wishlist.toggle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: id })
            })
            .then(res => res.json())
            .then(data => {
                this.loadingWishlist = false;
                if (data.status === 'unauthenticated') {
                    window.location.href = '{{ route('login') }}';
                    return;
                }
                this.inWishlist = data.in_wishlist;
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message, type: 'success' } }));
                window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: { count: data.count } }));
            })
            .catch(() => {
                this.loadingWishlist = false;
            });
        },
        addToCart(id) {
            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: id, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message, type: 'success' } }));
                    window.dispatchEvent(new CustomEvent('open-cart-drawer'));
                }
            })
            .catch(err => console.error(err));
        }
     }">

    <!-- Image Container with Hover Swap -->
    <div class="relative w-full aspect-[3/4] bg-zinc-100 overflow-hidden">
        <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $product->primary_image_url }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105 {{ $product->hover_image_url !== $product->primary_image_url ? 'group-hover:opacity-0' : '' }}" />

            @if($product->hover_image_url !== $product->primary_image_url)
                <img src="{{ $product->hover_image_url }}"
                     alt="{{ $product->name }}"
                     loading="lazy"
                     class="absolute inset-0 w-full h-full object-cover object-center opacity-0 group-hover:opacity-100 transition-all duration-700 ease-out group-hover:scale-105" />
            @endif
        </a>

        <!-- Badges -->
        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1.5 z-10">
            @if($hasSale)
                <span class="px-2 py-0.5 bg-rose-600 text-white text-[10px] font-bold tracking-wider uppercase">
                    -{{ $product->discount_percent }}%
                </span>
            @endif
            @if($isNew)
                <span class="px-2 py-0.5 bg-zinc-900 text-white text-[10px] font-bold tracking-wider uppercase">
                    MỚI
                </span>
            @endif
            @if(!$product->is_in_stock)
                <span class="px-2 py-0.5 bg-zinc-400 text-white text-[10px] font-bold tracking-wider uppercase">
                    HẾT HÀNG
                </span>
            @endif
        </div>

        <!-- Wishlist Button -->
        <button @click="toggleWishlist({{ $product->id }})"
                :disabled="loadingWishlist"
                type="button"
                aria-label="Thêm vào danh sách yêu thích"
                class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-zinc-700 hover:text-rose-600 flex items-center justify-center transition-all duration-200 shadow-sm opacity-90 group-hover:opacity-100 z-10">
            <svg class="w-4 h-4 transition-colors" :class="{ 'fill-rose-600 text-rose-600': inWishlist }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>

        <!-- Quick Add To Cart Button (slides up on hover) -->
        @if($product->is_in_stock)
            <div class="absolute bottom-0 inset-x-0 p-3 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-10 hidden sm:block">
                <button @click="addToCart({{ $product->id }})"
                        type="button"
                        class="w-full py-2.5 bg-zinc-950/95 hover:bg-zinc-900 text-white text-xs font-medium uppercase tracking-wider transition-colors shadow-lg flex items-center justify-center gap-1.5 backdrop-blur-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>Thêm Vào Giỏ</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Product Details -->
    <div class="pt-3 pb-2 flex flex-col flex-grow">
        @if($product->category)
            <a href="{{ route('category.show', $product->category->slug) }}" class="text-[11px] uppercase tracking-wider text-zinc-400 hover:text-zinc-600 transition-colors font-medium">
                {{ $product->category->name }}
            </a>
        @endif

        <h3 class="text-sm font-medium text-zinc-900 mt-1 line-clamp-1 group-hover:text-zinc-600 transition-colors">
            <a href="{{ route('product.show', $product->slug) }}">
                {{ $product->name }}
            </a>
        </h3>

        <!-- Price -->
        <div class="mt-1.5 flex items-center justify-between">
            <x-price :price="$product->price" :salePrice="$product->sale_price" size="sm" />

            <!-- Mobile Add Button -->
            <button @click="addToCart({{ $product->id }})"
                    type="button"
                    class="sm:hidden w-8 h-8 rounded-full bg-zinc-100 hover:bg-zinc-950 text-zinc-800 hover:text-white flex items-center justify-center transition-colors shadow-sm"
                    aria-label="Thêm vào giỏ">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </button>
        </div>
    </div>
</div>
