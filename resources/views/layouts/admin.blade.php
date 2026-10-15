<!DOCTYPE html>
<html lang="vi" class="h-full bg-zinc-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Quản Trị Hệ Thống' }} - NOVAWEAR Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-zinc-900 h-full flex" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 z-40 lg:hidden"
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- SIDEBAR -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-zinc-950 text-white flex flex-col justify-between transition-transform duration-300 transform lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <div>
            <!-- Admin Brand Logo -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-zinc-900">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-lg font-extrabold tracking-widest uppercase">NOVAWEAR</span>
                    <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase bg-amber-500 text-black">ADMIN</span>
                </a>
                <button type="button" @click="sidebarOpen = false" class="lg:hidden text-zinc-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-xs">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Sản phẩm</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    <span>Danh mục</span>
                </a>

                <a href="{{ route('admin.brands.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.brands.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Thương hiệu</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Đơn hàng</span>
                </a>

                <a href="{{ route('admin.customers.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.customers.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Khách hàng</span>
                </a>

                <a href="{{ route('admin.coupons.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.coupons.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <span>Mã giảm giá</span>
                </a>

                <a href="{{ route('admin.banners.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.banners.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Banners quảng cáo</span>
                </a>

                <a href="{{ route('admin.blogs.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.blogs.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>Bài viết Blog</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    <span>Đánh giá</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-none font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-zinc-800 text-white font-bold' : 'text-zinc-400 hover:text-white hover:bg-zinc-900' }}">
                    <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Cấu hình website</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Bottom: Back to Website & User Profile -->
        <div class="p-4 border-t border-zinc-900 space-y-3 text-xs">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-zinc-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Xem Website Bên Ngoài</span>
            </a>

            <div class="flex items-center justify-between pt-2 border-t border-zinc-900">
                <span class="text-zinc-300 font-semibold truncate">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium">Thoát</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN ADMIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

        <!-- Top Header Bar -->
        <header class="h-16 bg-white border-b border-zinc-200 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = true" class="lg:hidden text-zinc-700 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-800">
                    {{ $header ?? 'Bảng Điều Khiển Quản Trị' }}
                </h2>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold uppercase tracking-wider text-[10px]">
                    Hệ Thống Trực Tuyến
                </span>
                <span class="text-zinc-600 font-medium hidden sm:inline">{{ Auth::user()->email }}</span>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
            <!-- Flash Messages -->
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif
            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
