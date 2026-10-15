@php
    $cartCount = app(\App\Services\CartService::class)->getCount();
    $wishlistCount = app(\App\Services\WishlistService::class)->getCount();
    $isProductPage = request()->routeIs('product.show');
@endphp

@if(!$isProductPage)
<nav x-data="{
        cartCount: {{ $cartCount }},
        wishlistCount: {{ $wishlistCount }}
    }"
    x-init="
        window.addEventListener('cart-updated', (e) => { cartCount = e.detail.count });
        window.addEventListener('wishlist-updated', (e) => { wishlistCount = e.detail.count });
    "
    class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-zinc-200 lg:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.06)] pb-[env(safe-area-inset-bottom)]">
    
    <div class="grid grid-cols-5 h-14">
        <!-- 1. Home -->
        <a href="{{ route('home') }}"
           class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('home') ? 'text-zinc-950 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-[10px] mt-0.5 tracking-tight">Trang Chủ</span>
        </a>

        <!-- 2. Shop -->
        <a href="{{ route('shop') }}"
           class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('shop') || request()->routeIs('category.*') ? 'text-zinc-950 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('shop') || request()->routeIs('category.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span class="text-[10px] mt-0.5 tracking-tight">Cửa Hàng</span>
        </a>

        <!-- 3. Search -->
        <button type="button"
                @click="$dispatch('open-search')"
                class="flex flex-col items-center justify-center py-1 text-zinc-500 hover:text-zinc-900 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <span class="text-[10px] mt-0.5 tracking-tight">Tìm Kiếm</span>
        </button>

        <!-- 4. Wishlist -->
        <a href="{{ route('wishlist.index') }}"
           class="relative flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('wishlist.*') ? 'text-zinc-950 font-bold' : 'text-zinc-500 hover:text-zinc-900' }}">
            <div class="relative">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('wishlist.*') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span x-show="wishlistCount > 0"
                      x-text="wishlistCount"
                      class="absolute -top-1 -right-2 w-3.5 h-3.5 bg-zinc-950 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                </span>
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight">Yêu Thích</span>
        </a>

        <!-- 5. Cart Drawer -->
        <button type="button"
                @click="$dispatch('open-cart-drawer')"
                class="relative flex flex-col items-center justify-center py-1 text-zinc-500 hover:text-zinc-900 transition-colors">
            <div class="relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span x-show="cartCount > 0"
                      x-text="cartCount"
                      class="absolute -top-1 -right-2 w-3.5 h-3.5 bg-zinc-950 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                </span>
            </div>
            <span class="text-[10px] mt-0.5 tracking-tight">Giỏ Hàng</span>
        </button>
    </div>
</nav>
@endif
