@php
    $cartCount = app(\App\Services\CartService::class)->getCount();
    $wishlistCount = app(\App\Services\WishlistService::class)->getCount();
@endphp

<header x-data="{
        mobileMenuOpen: false,
        cartCount: {{ $cartCount }},
        wishlistCount: {{ $wishlistCount }},
        isScrolled: false
    }"
    x-init="
        window.addEventListener('scroll', () => { isScrolled = window.scrollY > 20 });
        window.addEventListener('cart-updated', (e) => { cartCount = e.detail.count });
        window.addEventListener('wishlist-updated', (e) => { wishlistCount = e.detail.count });
    "
    class="sticky top-0 z-40 bg-white/95 backdrop-blur-md transition-all duration-200 border-b border-zinc-100"
    :class="{ 'shadow-sm': isScrolled }">

    <!-- 1. Announcement Bar -->
    <div class="bg-zinc-950 text-white text-[11px] py-2 px-4 text-center font-medium tracking-wider flex items-center justify-center gap-3">
        <span>✨ MIỄN PHÍ GIAO HÀNG TOÀN QUỐC CHO ĐƠN HÀNG TỪ 500K</span>
        <span class="hidden sm:inline opacity-40">|</span>
        <span class="hidden sm:inline">NHẬP MÃ <strong class="text-amber-300">WELCOME10</strong> ĐỂ ĐƯỢC GIẢM 10%</span>
    </div>

    <!-- 2. Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center lg:hidden">
                <button type="button"
                        @click="mobileMenuOpen = true"
                        class="p-2 -ml-2 text-zinc-800 hover:text-black focus:outline-none"
                        aria-label="Mở menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <button type="button"
                        @click="$dispatch('open-search')"
                        class="p-2 text-zinc-800 hover:text-black focus:outline-none ml-1"
                        aria-label="Tìm kiếm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="group flex flex-col items-center">
                    <span class="text-2xl sm:text-3xl font-extrabold tracking-[0.2em] text-zinc-950 uppercase group-hover:opacity-90 transition-opacity">
                        NOVAWEAR
                    </span>
                    <span class="text-[9px] tracking-[0.35em] text-zinc-500 uppercase -mt-1 font-medium">
                        CONTEMPORARY
                    </span>
                </a>
            </div>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center space-x-7">
                <a href="{{ route('home') }}" class="text-xs uppercase tracking-wider font-semibold {{ request()->routeIs('home') ? 'text-black border-b-2 border-black pb-1' : 'text-zinc-600 hover:text-black transition-colors' }}">
                    Trang Chủ
                </a>
                <a href="{{ route('shop') }}" class="text-xs uppercase tracking-wider font-semibold {{ request()->routeIs('shop') && !request()->has('filter') && !request()->has('category') ? 'text-black border-b-2 border-black pb-1' : 'text-zinc-600 hover:text-black transition-colors' }}">
                    Bộ Sưu Tập
                </a>
                <a href="{{ route('category.show', 'ao-so-mi') }}" class="text-xs uppercase tracking-wider font-semibold text-zinc-600 hover:text-black transition-colors">
                    Sơ Mi
                </a>
                <a href="{{ route('category.show', 'ao-thun') }}" class="text-xs uppercase tracking-wider font-semibold text-zinc-600 hover:text-black transition-colors">
                    Áo Thun
                </a>
                <a href="{{ route('category.show', 'quan-jean') }}" class="text-xs uppercase tracking-wider font-semibold text-zinc-600 hover:text-black transition-colors">
                    Denim
                </a>
                <a href="{{ route('category.show', 'ao-khoac') }}" class="text-xs uppercase tracking-wider font-semibold text-zinc-600 hover:text-black transition-colors">
                    Áo Khoác
                </a>
                <a href="{{ route('shop', ['filter' => 'new']) }}" class="text-xs uppercase tracking-wider font-semibold text-zinc-900 hover:text-black transition-colors">
                    Hàng Mới
                </a>
                <a href="{{ route('shop', ['filter' => 'sale']) }}" class="text-xs uppercase tracking-wider font-bold text-rose-600 hover:text-rose-700 transition-colors flex items-center gap-1">
                    <span>Sale</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                </a>
                <a href="{{ route('blog.index') }}" class="text-xs uppercase tracking-wider font-semibold text-zinc-600 hover:text-black transition-colors">
                    Journal
                </a>
            </nav>

            <!-- Right Utilities Icons -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Search Button (Desktop) -->
                <button type="button"
                        @click="$dispatch('open-search')"
                        class="hidden lg:flex items-center gap-2 p-2 text-zinc-700 hover:text-black transition-colors"
                        aria-label="Tìm kiếm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Wishlist Icon -->
                <a href="{{ route('wishlist.index') }}"
                   class="relative p-2 text-zinc-700 hover:text-black transition-colors"
                   aria-label="Danh sách yêu thích">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span x-show="wishlistCount > 0"
                          x-text="wishlistCount"
                          class="absolute top-1 right-1 w-4 h-4 bg-zinc-900 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                    </span>
                </a>

                <!-- Cart Button -->
                <button type="button"
                        @click="$dispatch('open-cart-drawer')"
                        class="relative p-2 text-zinc-700 hover:text-black transition-colors"
                        aria-label="Giỏ hàng">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span x-show="cartCount > 0"
                          x-text="cartCount"
                          class="absolute top-1 right-1 w-4 h-4 bg-zinc-950 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                    </span>
                </button>

                <!-- Account / Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    @auth
                        <button @click="open = !open" @click.outside="open = false" type="button" class="flex items-center gap-1.5 p-2 text-zinc-700 hover:text-black transition-colors focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden md:inline text-xs font-semibold text-zinc-800 max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white border border-zinc-100 shadow-xl py-2 z-50 text-xs"
                             style="display: none;">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 font-bold text-amber-700 bg-amber-50 hover:bg-amber-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Trang Quản Trị
                                </a>
                                <hr class="my-1 border-zinc-100" />
                            @endif
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-zinc-700 hover:bg-zinc-50">Tài khoản của tôi</a>
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-zinc-700 hover:bg-zinc-50">Đơn hàng của tôi</a>
                            <a href="{{ route('account.addresses') }}" class="block px-4 py-2 text-zinc-700 hover:bg-zinc-50">Sổ địa chỉ</a>
                            <hr class="my-1 border-zinc-100" />
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="p-2 text-zinc-700 hover:text-black transition-colors flex items-center gap-1.5" aria-label="Đăng nhập">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden md:inline text-xs font-semibold uppercase tracking-wider">Đăng Nhập</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 z-50 lg:hidden"
         @click="mobileMenuOpen = false"
         style="display: none;"></div>

    <div x-show="mobileMenuOpen"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 w-4/5 max-w-sm bg-white shadow-2xl z-50 overflow-y-auto flex flex-col justify-between lg:hidden"
         style="display: none;">

        <div class="p-5">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                <span class="text-xl font-extrabold tracking-widest text-zinc-950 uppercase">NOVAWEAR</span>
                <button type="button" @click="mobileMenuOpen = false" class="text-zinc-500 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="py-4 space-y-3">
                <a href="{{ route('home') }}" class="block text-sm font-semibold uppercase tracking-wider text-zinc-900 py-1">Trang Chủ</a>
                <a href="{{ route('shop') }}" class="block text-sm font-semibold uppercase tracking-wider text-zinc-900 py-1">Tất Cả Sản Phẩm</a>
                <a href="{{ route('category.show', 'ao-so-mi') }}" class="block text-sm font-medium text-zinc-600 hover:text-black py-1">Áo Sơ Mi</a>
                <a href="{{ route('category.show', 'ao-thun') }}" class="block text-sm font-medium text-zinc-600 hover:text-black py-1">Áo Thun Oversize</a>
                <a href="{{ route('category.show', 'quan-jean') }}" class="block text-sm font-medium text-zinc-600 hover:text-black py-1">Quần Denim</a>
                <a href="{{ route('category.show', 'ao-khoac') }}" class="block text-sm font-medium text-zinc-600 hover:text-black py-1">Áo Khoác & Blazer</a>
                <a href="{{ route('shop', ['filter' => 'new']) }}" class="block text-sm font-semibold text-zinc-900 py-1">Hàng Mới Về</a>
                <a href="{{ route('shop', ['filter' => 'sale']) }}" class="block text-sm font-bold text-rose-600 py-1">Ưu Đãi Sale</a>
                <a href="{{ route('blog.index') }}" class="block text-sm font-semibold text-zinc-700 py-1">NOVAWEAR Journal</a>
            </div>
        </div>

        <div class="p-5 border-t border-zinc-100 bg-zinc-50 space-y-3">
            @auth
                <div class="text-xs text-zinc-600 mb-2">Đang đăng nhập: <strong>{{ Auth::user()->name }}</strong></div>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block w-full py-2.5 bg-amber-600 text-white text-center text-xs font-bold uppercase tracking-wider">Trang Quản Trị</a>
                @endif
                <a href="{{ route('account.dashboard') }}" class="block w-full py-2.5 bg-zinc-900 text-white text-center text-xs font-semibold uppercase tracking-wider">Tài Khoản Của Tôi</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full py-2 text-rose-600 text-center text-xs font-semibold">Đăng Xuất</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}" class="py-2.5 bg-zinc-900 text-white text-center text-xs font-semibold uppercase tracking-wider">Đăng Nhập</a>
                    <a href="{{ route('register') }}" class="py-2.5 bg-white border border-zinc-900 text-zinc-900 text-center text-xs font-semibold uppercase tracking-wider">Đăng Ký</a>
                </div>
            @endauth
        </div>
    </div>
</header>
