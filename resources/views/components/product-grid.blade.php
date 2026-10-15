@props([
    'products',
    'cols' => 4,
])

@php
    $gridCols = match($cols) {
        2 => 'grid-cols-2',
        3 => 'grid-cols-2 md:grid-cols-3',
        default => 'grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
    };
@endphp

@if($products->isEmpty())
    <div class="py-16 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-zinc-100 text-zinc-400 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <h3 class="text-base font-medium text-zinc-900">Không tìm thấy sản phẩm nào</h3>
        <p class="text-xs text-zinc-500 mt-1 max-w-sm mx-auto">Vui lòng thử điều chỉnh bộ lọc hoặc từ khóa tìm kiếm để khám phá các sản phẩm khác.</p>
        <div class="mt-6">
            <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-zinc-900 text-white text-xs uppercase tracking-wider hover:bg-zinc-800 transition-colors">
                Xem Tất Cả Sản Phẩm
            </a>
        </div>
    </div>
@else
    <div class="grid {{ $gridCols }} gap-x-4 gap-y-8 sm:gap-x-6 sm:gap-y-10">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
@endif
