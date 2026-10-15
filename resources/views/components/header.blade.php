@php
    $cartCount = app(\App\Services\CartService::class)->getCount();
    $wishlistCount = app(\App\Services\WishlistService::class)->getCount();
    $categories = \App\Models\Category::where('status', 'active')->orderBy('sort_order')->get();
@endphp

<header x-data="{
        mobileMenuOpen: false,
        cartCount: {{ $cartCount }},
        wishlistCount: {{ $wishlistCount }},
        isScrolled: false,
        activeDropdown: null
    }"
    x-init="
        window.addEventListener('scroll', () => { isScrolled = window.scrollY > 20 });
        window.addEventListener('cart-updated', (e) => { cartCount = e.detail.count });
        window.addEventListener('wishlist-updated', (e) => { wishlistCount = e.detail.count });
    "
    class="sticky top-0 z-40 bg-white/95 backdrop-blur-md transition-all duration-300 border-b border-zinc-200"
    :class="{ 'shadow-sm': isScrolled }">

    <!-- 1. Top Announcement Bar -->
    <div class="bg-zinc-950 text-white text-[11px] py-2 px-4 font-medium tracking-wider">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="hidden md:flex items-center gap-4 text-zinc-400">
                <span class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    Hotline: <strong class="text-zinc-200 font-semibold">1900 888 999</strong>
                </span>
                <span>•</span>
                <span>Hỗ trợ 24/7 toàn quốc</span>
            </div>

            <div class="flex-1 text-center font-medium">
                <span>✨ MIỄN PHÍ VẬN CHUYỂN TOÀN QUỐC ĐƠN TỪ 500K</span>
                <span class="mx-2 opacity-40">|</span>
                <span>MÃ ƯU ĐÃI: <strong class="text-amber-300 font-bold tracking-widest">WELCOME10</strong> (-10%)</span>
            </div>

            <div class="hidden md:flex items-center gap-4 text-zinc-400">
                <a href="{{ route('about') }}" class="hover:text-white transition-colors">Về NOVAWEAR</a>
                <span>•</span>
                <a href="{{ route('contact') }}" class="hover:text-white transition-colors">Hệ Thống Showroom</a>
            </div>
        </div>
    </div>

    <!-- 2. Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">

            <!-- Mobile Left: Hamburger Button & Search -->
            <div class="flex items-center gap-1 lg:hidden">
                <button type="button"
                        @click="mobileMenuOpen = true"
                        class="p-2 -ml-2 text-zinc-900 hover:text-black focus:outline-none"
                        aria-label="Mở menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <button type="button"
                        @click="$dispatch('open-search')"
                        class="p-2 text-zinc-900 hover:text-black focus:outline-none"
                        aria-label="Tìm kiếm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- Brand Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="group flex flex-col items-center">
                    <span class="text-2xl sm:text-3xl font-extrabold tracking-[0.25em] text-zinc-950 uppercase group-hover:opacity-90 transition-opacity">
                        NOVAWEAR
                    </span>
                    <span class="text-[8.5px] tracking-[0.4em] text-zinc-400 uppercase -mt-1 font-medium group-hover:text-zinc-600 transition-colors">
                        CONTEMPORARY
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation Menu -->
            <nav class="hidden lg:flex items-center space-x-6 xl:space-x-8 text-xs font-semibold uppercase tracking-wider">
                
                <!-- Trang Chủ -->
                <a href="{{ route('home') }}"
                   class="py-2 {{ request()->routeIs('home') ? 'text-black border-b-2 border-black font-bold' : 'text-zinc-700 hover:text-black transition-colors' }}">
                    Trang Chủ
                </a>

                <!-- Shop (with Mega Menu Dropdown) -->
                <div class="relative group" @mouseenter="activeDropdown = 'shop'" @mouseleave="activeDropdown = null">
                    <a href="{{ route('shop') }}"
                       class="py-2 flex items-center gap-1 {{ request()->routeIs('shop') && !request()->hasAny(['filter', 'category']) ? 'text-black border-b-2 border-black font-bold' : 'text-zinc-700 hover:text-black transition-colors' }}">
                        <span>Cửa Hàng</span>
                        <svg class="w-3 h-3 text-zinc-400 group-hover:text-black transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>

                    <!-- Mega Menu Container -->
                    <div x-show="activeDropdown === 'shop'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute left-1/2 -translate-x-1/2 top-full w-[760px] bg-white border border-zinc-200 shadow-2xl p-6 z-50 normal-case"
                         style="display: none;">
                        <div class="grid grid-cols-4 gap-6">
                            <!-- Col 1: Nam -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-950 pb-1 border-b border-zinc-200">Thời Trang Nam</h4>
                                <ul class="space-y-2 text-xs text-zinc-600">
                                    <li><a href="{{ route('category.show', 'ao-so-mi') }}" class="hover:text-black hover:underline block py-0.5">Áo Sơ Mi Cao Cấp</a></li>
                                    <li><a href="{{ route('category.show', 'ao-thun') }}" class="hover:text-black hover:underline block py-0.5">Áo Thun Cotton Oversize</a></li>
                                    <li><a href="{{ route('category.show', 'ao-polo') }}" class="hover:text-black hover:underline block py-0.5">Áo Polo Dệt Kim</a></li>
                                    <li><a href="{{ route('category.show', 'quan-jean') }}" class="hover:text-black hover:underline block py-0.5">Quần Jean Denim</a></li>
                                    <li><a href="{{ route('category.show', 'quan-tay') }}" class="hover:text-black hover:underline block py-0.5">Quần Tây & Chinos</a></li>
                                    <li><a href="{{ route('category.show', 'ao-khoac') }}" class="hover:text-black hover:underline block py-0.5">Áo Khoác & Blazer</a></li>
                                </ul>
                            </div>

                            <!-- Col 2: Nữ & Phụ Kiện -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-950 pb-1 border-b border-zinc-200">Nữ & Phụ Kiện</h4>
                                <ul class="space-y-2 text-xs text-zinc-600">
                                    <li><a href="{{ route('category.show', 'vay-dam') }}" class="hover:text-black hover:underline block py-0.5">Váy & Đầm Liền</a></li>
                                    <li><a href="{{ route('category.show', 'phu-kien') }}" class="hover:text-black hover:underline block py-0.5">Túi Xách & Balo</a></li>
                                    <li><a href="{{ route('category.show', 'phu-kien') }}" class="hover:text-black hover:underline block py-0.5">Thắt Lưng Da Bò Ý</a></li>
                                    <li><a href="{{ route('category.show', 'phu-kien') }}" class="hover:text-black hover:underline block py-0.5">Mũ & Khăn Choàng</a></li>
                                    <li><a href="{{ route('shop') }}" class="text-zinc-900 font-semibold hover:underline block pt-2">Xem Tất Cả Sản Phẩm →</a></li>
                                </ul>
                            </div>

                            <!-- Col 3: Bộ Sưu Tập -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-950 pb-1 border-b border-zinc-200">Khám Phá</h4>
                                <ul class="space-y-2 text-xs text-zinc-600">
                                    <li><a href="{{ route('shop', ['filter' => 'new']) }}" class="hover:text-black hover:underline block py-0.5 font-medium text-emerald-700">★ Bộ Sưu Tập Mới 2026</a></li>
                                    <li><a href="{{ route('shop', ['filter' => 'bestseller']) }}" class="hover:text-black hover:underline block py-0.5">Sản Phẩm Bán Chạy Nhất</a></li>
                                    <li><a href="{{ route('shop', ['filter' => 'sale']) }}" class="hover:text-rose-700 hover:underline block py-0.5 font-bold text-rose-600">Khuyến Mãi Lên Đến 50%</a></li>
                                    <li><a href="{{ route('blog.index') }}" class="hover:text-black hover:underline block py-0.5">Cẩm Nang Phối Đồ</a></li>
                                </ul>
                            </div>

                            <!-- Col 4: Visual Card -->
                            <div class="bg-zinc-50 p-3 border border-zinc-100 flex flex-col justify-between">
                                <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=600&q=80" alt="New Collection" class="w-full h-32 object-cover mb-2" />
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider">Lookbook 2026</span>
                                    <h5 class="text-xs font-bold text-zinc-900 mt-0.5">Capsule Wardrobe</h5>
                                    <a href="{{ route('shop') }}" class="mt-2 inline-block text-[11px] font-bold uppercase text-zinc-950 hover:underline">Khám phá ngay →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Men (Thời Trang Nam) -->
                <div class="relative group" @mouseenter="activeDropdown = 'men'" @mouseleave="activeDropdown = null">
                    <a href="{{ route('shop', ['category' => 'ao-so-mi']) }}"
                       class="py-2 flex items-center gap-1 text-zinc-700 hover:text-black transition-colors">
                        <span>Nam</span>
                        <svg class="w-3 h-3 text-zinc-400 group-hover:text-black transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>

                    <div x-show="activeDropdown === 'men'"
                         x-transition
                         class="absolute left-0 top-full w-52 bg-white border border-zinc-200 shadow-xl py-2 z-50 normal-case"
                         style="display: none;">
                        <a href="{{ route('category.show', 'ao-so-mi') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Áo Sơ Mi</a>
                        <a href="{{ route('category.show', 'ao-thun') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Áo Thun Trơn & Oversize</a>
                        <a href="{{ route('category.show', 'ao-polo') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Áo Polo Thể Thao</a>
                        <a href="{{ route('category.show', 'quan-jean') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Quần Jean Denim</a>
                        <a href="{{ route('category.show', 'quan-tay') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Quần Tây & Kaki</a>
                        <a href="{{ route('category.show', 'ao-khoac') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Áo Khoác Nam</a>
                    </div>
                </div>

                <!-- Women (Thời Trang Nữ) -->
                <div class="relative group" @mouseenter="activeDropdown = 'women'" @mouseleave="activeDropdown = null">
                    <a href="{{ route('category.show', 'vay-dam') }}"
                       class="py-2 flex items-center gap-1 text-zinc-700 hover:text-black transition-colors">
                        <span>Nữ</span>
                        <svg class="w-3 h-3 text-zinc-400 group-hover:text-black transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>

                    <div x-show="activeDropdown === 'women'"
                         x-transition
                         class="absolute left-0 top-full w-48 bg-white border border-zinc-200 shadow-xl py-2 z-50 normal-case"
                         style="display: none;">
                        <a href="{{ route('category.show', 'vay-dam') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Váy & Đầm Thiết Kế</a>
                        <a href="{{ route('category.show', 'ao-thun') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Áo Thun Nữ Tối Giản</a>
                        <a href="{{ route('category.show', 'quan-jean') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Quần Jean Ống Suông</a>
                        <a href="{{ route('category.show', 'phu-kien') }}" class="block px-4 py-2 text-xs text-zinc-700 hover:bg-zinc-50 hover:text-black">Túi Xách Nữ</a>
                    </div>
                </div>

                <!-- New Arrivals -->
                <a href="{{ route('shop', ['filter' => 'new']) }}"
                   class="py-2 flex items-center gap-1.5 text-zinc-900 hover:text-black transition-colors">
                    <span>Hàng Mới</span>
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </a>

                <!-- Sale Hot -->
                <a href="{{ route('shop', ['filter' => 'sale']) }}"
                   class="py-2 flex items-center gap-1.5 font-bold text-rose-600 hover:text-rose-700 transition-colors">
                    <span>Sale</span>
                    <span class="px-1.5 py-0.2 bg-rose-600 text-white text-[9px] font-extrabold tracking-wider rounded-none">HOT</span>
                </a>

                <!-- Blog Journal -->
                <a href="{{ route('blog.index') }}"
                   class="py-2 {{ request()->routeIs('blog.*') ? 'text-black border-b-2 border-black font-bold' : 'text-zinc-700 hover:text-black transition-colors' }}">
                    Journal
                </a>

                <!-- Giới Thiệu -->
                <a href="{{ route('about') }}"
                   class="py-2 {{ request()->routeIs('about') ? 'text-black border-b-2 border-black font-bold' : 'text-zinc-700 hover:text-black transition-colors' }}">
                    Về Chúng Tôi
                </a>
            </nav>

            <!-- Right Utility Icons (Search, Wishlist, Cart, Account) -->
            <div class="flex items-center space-x-1 sm:space-x-3">
                <!-- Search Icon (Desktop) -->
                <button type="button"
                        @click="$dispatch('open-search')"
                        class="hidden sm:flex items-center gap-2 px-3 py-1.5 text-zinc-700 hover:text-black border border-zinc-200 hover:border-zinc-400 bg-zinc-50 transition-all rounded-none"
                        aria-label="Tìm kiếm">
                    <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="text-xs text-zinc-400 font-normal">Tìm kiếm...</span>
                    <span class="text-[10px] text-zinc-400 font-mono border border-zinc-300 px-1 py-0.5 bg-white ml-2">⌘K</span>
                </button>

                <!-- Wishlist Button with Badge -->
                <a href="{{ route('wishlist.index') }}"
                   class="relative p-2.5 text-zinc-800 hover:text-black hover:bg-zinc-100 transition-colors"
                   title="Sản phẩm yêu thích"
                   aria-label="Danh sách yêu thích">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span x-show="wishlistCount > 0"
                          x-text="wishlistCount"
                          class="absolute top-1 right-1 w-4 h-4 bg-zinc-950 text-white text-[10px] font-bold flex items-center justify-center">
                    </span>
                </a>

                <!-- Cart Button with Slide-over Drawer -->
                <button type="button"
                        @click="$dispatch('open-cart-drawer')"
                        class="relative p-2.5 text-zinc-800 hover:text-black hover:bg-zinc-100 transition-colors"
                        title="Giỏ hàng"
                        aria-label="Giỏ hàng">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span x-show="cartCount > 0"
                          x-text="cartCount"
                          class="absolute top-1 right-1 w-4 h-4 bg-zinc-950 text-white text-[10px] font-bold flex items-center justify-center">
                    </span>
                </button>

                <!-- User Account / Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    @auth
                        <button @click="open = !open" @click.outside="open = false" type="button" class="flex items-center gap-2 p-1.5 sm:px-2.5 sm:py-1.5 border border-zinc-200 hover:border-zinc-400 bg-white text-zinc-800 transition-colors focus:outline-none">
                            <span class="w-6 h-6 rounded-full bg-zinc-950 text-white text-[11px] font-bold flex items-center justify-center uppercase">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </span>
                            <span class="hidden md:inline text-xs font-semibold text-zinc-900 max-w-[90px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open"
                             x-transition
                             class="absolute right-0 mt-2 w-52 bg-white border border-zinc-200 shadow-2xl py-2 z-50 text-xs"
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-zinc-100">
                                <div class="font-bold text-zinc-900 truncate">{{ Auth::user()->name }}</div>
                                <div class="text-[11px] text-zinc-400 truncate">{{ Auth::user()->email }}</div>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 font-bold text-amber-700 bg-amber-50 hover:bg-amber-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Trang Quản Trị
                                </a>
                                <hr class="my-1 border-zinc-100" />
                            @endif

                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-zinc-700 hover:bg-zinc-50 hover:text-black">Tài khoản của tôi</a>
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-zinc-700 hover:bg-zinc-50 hover:text-black">Lịch sử đơn hàng</a>
                            <a href="{{ route('account.addresses') }}" class="block px-4 py-2 text-zinc-700 hover:bg-zinc-50 hover:text-black">Sổ địa chỉ giao hàng</a>
                            <hr class="my-1 border-zinc-100" />
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 font-medium">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="p-2 sm:px-3 sm:py-1.5 text-zinc-800 hover:text-white hover:bg-zinc-950 border border-zinc-300 hover:border-zinc-950 transition-all flex items-center gap-1.5" aria-label="Đăng nhập">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="hidden sm:inline text-xs font-semibold uppercase tracking-wider">Đăng Nhập</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Mobile Navigation Drawer -->
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
            <!-- Header inside drawer -->
            <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
                <span class="text-xl font-extrabold tracking-widest text-zinc-950 uppercase">NOVAWEAR</span>
                <button type="button" @click="mobileMenuOpen = false" class="text-zinc-500 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Mobile Quick Search -->
            <div class="pt-4 pb-2">
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Tìm sản phẩm..." class="w-full text-xs py-2.5 pl-9 pr-3 border border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                    <svg class="w-4 h-4 text-zinc-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
            </div>

            <!-- Navigation Links -->
            <div class="py-3 space-y-1 text-xs">
                <a href="{{ route('home') }}" class="block font-bold uppercase tracking-wider text-zinc-900 py-2.5 border-b border-zinc-100">Trang Chủ</a>
                <a href="{{ route('shop') }}" class="block font-bold uppercase tracking-wider text-zinc-900 py-2.5 border-b border-zinc-100">Tất Cả Sản Phẩm</a>
                
                <!-- Accordion Menu Categories -->
                <div x-data="{ expanded: false }" class="border-b border-zinc-100 py-2">
                    <button type="button" @click="expanded = !expanded" class="w-full flex items-center justify-between font-bold uppercase tracking-wider text-zinc-900 py-1">
                        <span>Danh Mục Sản Phẩm</span>
                        <svg class="w-4 h-4 transition-transform text-zinc-400" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="expanded" x-collapse class="pl-3 pt-2 space-y-2 text-zinc-600 font-medium">
                        <a href="{{ route('category.show', 'ao-so-mi') }}" class="block py-1 hover:text-black">Áo Sơ Mi Nam</a>
                        <a href="{{ route('category.show', 'ao-thun') }}" class="block py-1 hover:text-black">Áo Thun Trơn & Oversize</a>
                        <a href="{{ route('category.show', 'ao-polo') }}" class="block py-1 hover:text-black">Áo Polo Dệt Kim</a>
                        <a href="{{ route('category.show', 'quan-jean') }}" class="block py-1 hover:text-black">Quần Jean Denim</a>
                        <a href="{{ route('category.show', 'quan-tay') }}" class="block py-1 hover:text-black">Quần Tây & Chinos</a>
                        <a href="{{ route('category.show', 'ao-khoac') }}" class="block py-1 hover:text-black">Áo Khoác & Blazer</a>
                        <a href="{{ route('category.show', 'vay-dam') }}" class="block py-1 hover:text-black">Váy & Đầm Nữ</a>
                        <a href="{{ route('category.show', 'phu-kien') }}" class="block py-1 hover:text-black">Phụ Kiện Thời Trang</a>
                    </div>
                </div>

                <a href="{{ route('shop', ['filter' => 'new']) }}" class="flex items-center justify-between font-bold uppercase tracking-wider text-emerald-700 py-2.5 border-b border-zinc-100">
                    <span>Hàng Mới Về 2026</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </a>

                <a href="{{ route('shop', ['filter' => 'sale']) }}" class="flex items-center justify-between font-bold uppercase tracking-wider text-rose-600 py-2.5 border-b border-zinc-100">
                    <span>Ưu Đãi Giảm Giá</span>
                    <span class="px-1.5 py-0.5 bg-rose-600 text-white text-[9px] font-bold">HOT</span>
                </a>

                <a href="{{ route('blog.index') }}" class="block font-semibold uppercase tracking-wider text-zinc-800 py-2.5 border-b border-zinc-100">Journal & Tin Tức</a>
                <a href="{{ route('about') }}" class="block font-semibold uppercase tracking-wider text-zinc-800 py-2.5 border-b border-zinc-100">Về Thương Hiệu</a>
                <a href="{{ route('contact') }}" class="block font-semibold uppercase tracking-wider text-zinc-800 py-2.5">Liên Hệ & Showroom</a>
            </div>
        </div>

        <!-- Drawer Footer: Auth / Contact -->
        <div class="p-5 border-t border-zinc-200 bg-zinc-50 space-y-3">
            @auth
                <div class="text-xs text-zinc-600 mb-2">Xin chào: <strong>{{ Auth::user()->name }}</strong></div>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block w-full py-2.5 bg-amber-600 text-white text-center text-xs font-bold uppercase tracking-wider">Trang Quản Trị</a>
                @endif
                <a href="{{ route('account.dashboard') }}" class="block w-full py-2.5 bg-zinc-950 text-white text-center text-xs font-semibold uppercase tracking-wider">Tài Khoản Của Tôi</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full py-2 text-rose-600 text-center text-xs font-semibold">Đăng Xuất</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}" class="py-2.5 bg-zinc-950 text-white text-center text-xs font-semibold uppercase tracking-wider">Đăng Nhập</a>
                    <a href="{{ route('register') }}" class="py-2.5 bg-white border border-zinc-950 text-zinc-950 text-center text-xs font-semibold uppercase tracking-wider">Đăng Ký</a>
                </div>
            @endauth

            <div class="text-center text-[11px] text-zinc-400 pt-2">
                Hotline hỗ trợ: <strong>1900 888 999</strong> (8:30 - 22:00)
            </div>
        </div>
    </div>
</header>
