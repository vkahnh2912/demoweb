<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle ?? ($title ?? 'NOVAWEAR - Thương Hiệu Thời Trang Đương Đại Tối Giản') }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Khám phá bộ sưu tập thời trang cao cấp đương đại từ NOVAWEAR. Áo sơ mi, áo thun, blazer, quần denim tối giản, chất liệu tuyển chọn.' }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>✦</text></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased text-zinc-900 bg-white min-h-full flex flex-col selection:bg-black selection:text-white"
      x-data="{
          toasts: [],
          addToast(message, type = 'success') {
              const id = Date.now();
              this.toasts.push({ id, message, type });
              setTimeout(() => {
                  this.toasts = this.toasts.filter(t => t.id !== id);
              }, 4000);
          }
      }"
      @toast.window="addToast($event.detail.message, $event.detail.type)">

    <!-- Header Component -->
    <x-header />

    <!-- Main Content Area -->
    <main class="flex-grow pb-16 lg:pb-0">
        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" />
            @endif
            @if(session('error'))
                <x-alert type="error" :message="session('error')" />
            @endif
            @if(session('warning'))
                <x-alert type="warning" :message="session('warning')" />
            @endif
            @if(session('info'))
                <x-alert type="info" :message="session('info')" />
            @endif
        </div>

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer Component -->
    <x-footer />

    <!-- Interactive Global Modals & Drawers -->
    <x-search />
    <x-cart />
    <x-mobile-drawer />
    <x-mobile-bottom-nav />

    <!-- Toast Notification System -->
    <div class="fixed bottom-20 sm:bottom-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none px-4 sm:px-0">
        <template x-for="t in toasts" :key="t.id">
            <div x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="pointer-events-auto bg-zinc-950 text-white text-xs px-4 py-3 rounded-none shadow-2xl flex items-center justify-between border-l-4"
                 :class="{
                    'border-emerald-500': t.type === 'success',
                    'border-rose-500': t.type === 'error',
                    'border-amber-500': t.type === 'warning',
                    'border-sky-500': t.type === 'info'
                 }">
                <span x-text="t.message"></span>
                <button type="button" @click="toasts = toasts.filter(item => item.id !== t.id)" class="text-zinc-400 hover:text-white ml-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </template>
    </div>

    @stack('scripts')
</body>
</html>
