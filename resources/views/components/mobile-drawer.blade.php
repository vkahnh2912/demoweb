@php
    $drawerCategories = \App\Models\Category::where('status', 'active')->orderBy('sort_order')->get();
@endphp

<div x-data="{
        open: false,
        expandedCat: false,
        expandedMen: false,
        expandedWomen: false
    }"
    @open-mobile-menu.window="open = true"
    @keydown.escape.window="open = false"
    class="relative z-50 lg:hidden">

    <!-- Backdrop -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"
         @click="open = false"
         style="display: none;"></div>

    <!-- Drawer Panel -->
    <div x-show="open"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 w-[85vw] max-w-sm bg-white shadow-2xl overflow-y-auto flex flex-col justify-between"
         style="display: none;">

        <div class="p-5">
            <!-- Header inside drawer -->
            <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
                <a href="{{ route('home') }}" @click="open = false" class="flex flex-col">
                    <span class="text-xl font-extrabold tracking-[0.22em] text-zinc-950 uppercase">NOVAWEAR</span>
                    <span class="text-[8px] tracking-[0.38em] text-zinc-400 uppercase -mt-0.5 font-medium">CONTEMPORARY</span>
                </a>
                <button type="button"
                        @click="open = false"
                        class="w-10 h-10 -mr-2 text-zinc-500 hover:text-black flex items-center justify-center rounded-full hover:bg-zinc-100 transition-colors"
                        aria-label="Đóng menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Quick Search -->
            <div class="pt-4 pb-3">
                <form action="{{ route('search') }}" method="GET" class="relative" @submit="open = false">
                    <input type="text"
                           name="q"
                           placeholder="Tìm kiếm sản phẩm, áo, quần..."
                           class="w-full text-xs py-2.5 pl-9 pr-3 border border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-zinc-50 font-normal" />
                    <svg class="w-4 h-4 text-zinc-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </form>
            </div>

            <!-- Navigation Links -->
            <nav class="py-2 space-y-1 text-xs">
                <a href="{{ route('home') }}"
                   @click="open = false"
                   class="flex items-center justify-between font-bold uppercase tracking-wider py-3 border-b border-zinc-100 {{ request()->routeIs('home') ? 'text-black' : 'text-zinc-800' }}">
                    <span>Trang Chủ</span>
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <a href="{{ route('shop') }}"
                   @click="open = false"
                   class="flex items-center justify-between font-bold uppercase tracking-wider py-3 border-b border-zinc-100 {{ request()->routeIs('shop') && !request()->hasAny(['filter', 'category']) ? 'text-black' : 'text-zinc-800' }}">
                    <span>Tất Cả Sản Phẩm</span>
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                
                <!-- Accordion Menu Categories -->
                <div class="border-b border-zinc-100 py-2">
                    <button type="button"
                            @click="expandedCat = !expandedCat"
                            class="w-full flex items-center justify-between font-bold uppercase tracking-wider text-zinc-900 py-1.5 focus:outline-none">
                        <span>Danh Mục Sản Phẩm</span>
                        <svg class="w-4 h-4 transition-transform duration-200 text-zinc-400" :class="{ 'rotate-180': expandedCat }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="expandedCat" x-collapse class="pl-3 pt-2 space-y-1 text-zinc-600 font-medium">
                        @foreach($drawerCategories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}"
                               @click="open = false"
                               class="flex items-center justify-between py-2 text-zinc-700 hover:text-black">
                                <span>{{ $cat->name }}</span>
                                <span class="text-[11px] text-zinc-400">({{ $cat->products()->count() }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- New Arrivals -->
                <a href="{{ route('shop', ['filter' => 'new']) }}"
                   @click="open = false"
                   class="flex items-center justify-between font-bold uppercase tracking-wider text-emerald-700 py-3 border-b border-zinc-100">
                    <span class="flex items-center gap-2">
                        <span>Hàng Mới Về 2026</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </span>
                    <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 bg-emerald-100 text-emerald-800">Mới</span>
                </a>

                <!-- Sale Hot -->
                <a href="{{ route('shop', ['filter' => 'sale']) }}"
                   @click="open = false"
                   class="flex items-center justify-between font-bold uppercase tracking-wider text-rose-600 py-3 border-b border-zinc-100">
                    <span>Ưu Đãi Giảm Giá</span>
                    <span class="px-1.5 py-0.5 bg-rose-600 text-white text-[9px] font-bold">HOT</span>
                </a>

                <!-- Journal -->
                <a href="{{ route('blog.index') }}"
                   @click="open = false"
                   class="flex items-center justify-between font-semibold uppercase tracking-wider text-zinc-800 py-3 border-b border-zinc-100">
                    <span>Journal & Cẩm Nang</span>
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>

                <!-- About & Showroom -->
                <a href="{{ route('about') }}"
                   @click="open = false"
                   class="block font-semibold uppercase tracking-wider text-zinc-800 py-3 border-b border-zinc-100">
                    Về Thương Hiệu
                </a>
                <a href="{{ route('contact') }}"
                   @click="open = false"
                   class="block font-semibold uppercase tracking-wider text-zinc-800 py-3">
                    Liên Hệ & Showroom
                </a>
            </nav>
        </div>

        <!-- Drawer Footer: Auth / Contact -->
        <div class="p-5 border-t border-zinc-200 bg-zinc-50 space-y-3">
            @auth
                <div class="flex items-center gap-3 pb-2 border-b border-zinc-200">
                    <span class="w-9 h-9 rounded-full bg-zinc-950 text-white text-xs font-bold flex items-center justify-center uppercase shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </span>
                    <div class="min-w-0">
                        <div class="font-bold text-xs text-zinc-900 truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] text-zinc-500 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       @click="open = false"
                       class="block w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-center text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                        ★ Trang Quản Trị Admin
                    </a>
                @endif

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="{{ route('account.dashboard') }}"
                       @click="open = false"
                       class="py-2.5 bg-zinc-950 text-white text-center font-semibold uppercase tracking-wider">
                        Tài Khoản
                    </a>
                    <a href="{{ route('account.orders') }}"
                       @click="open = false"
                       class="py-2.5 bg-white border border-zinc-300 text-zinc-800 text-center font-semibold uppercase tracking-wider">
                        Đơn Hàng
                    </a>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full py-1.5 text-rose-600 text-center text-xs font-semibold hover:underline">
                        Đăng Xuất
                    </button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2.5">
                    <a href="{{ route('login') }}"
                       @click="open = false"
                       class="py-3 bg-zinc-950 text-white text-center text-xs font-bold uppercase tracking-wider shadow-sm">
                        Đăng Nhập
                    </a>
                    <a href="{{ route('register') }}"
                       @click="open = false"
                       class="py-3 bg-white border border-zinc-900 text-zinc-950 text-center text-xs font-bold uppercase tracking-wider">
                        Đăng Ký
                    </a>
                </div>
            @endauth

            <div class="pt-2 text-center text-[11px] text-zinc-500">
                <a href="tel:1900888999" class="inline-flex items-center gap-1.5 font-bold text-zinc-900 hover:underline">
                    <svg class="w-3.5 h-3.5 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>Hotline: 1900 888 999</span>
                </a>
                <span class="block text-[10px] text-zinc-400 mt-0.5">8:30 - 22:00 (Tất cả các ngày trong tuần)</span>
            </div>
        </div>
    </div>
</div>
