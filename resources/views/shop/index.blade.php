@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10" x-data="{ mobileFilterOpen: false }">

    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Cửa Hàng', 'url' => route('shop')],
        ['label' => $currentCategory ? $currentCategory->name : 'Tất Cả Sản Phẩm']
    ]" />

    <!-- Page Header & Title -->
    <div class="flex flex-col md:flex-row md:items-end justify-between py-6 border-b border-zinc-200 gap-4">
        <div>
            <h1 class="text-2xl sm:text-4xl font-extrabold uppercase tracking-tight text-zinc-950">
                {{ $currentCategory ? $currentCategory->name : 'Tất Cả Sản Phẩm' }}
            </h1>
            <p class="text-xs text-zinc-500 mt-1 max-w-xl">
                {{ $currentCategory ? $currentCategory->description : 'Khám phá các thiết kế thời trang đương đại, may đo chỉn chu với sợi vải tự nhiên thoáng mát.' }}
            </p>
        </div>

        <!-- Sort dropdown & Mobile filter toggle -->
        <div class="flex items-center gap-3">
            <!-- Mobile Filter Button -->
            <button type="button"
                    @click="mobileFilterOpen = true"
                    class="lg:hidden inline-flex items-center gap-2 px-4 py-2 border border-zinc-900 text-xs font-semibold uppercase tracking-wider text-zinc-900 hover:bg-zinc-900 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span>Bộ Lọc</span>
            </button>

            <!-- Sorting Select -->
            <div class="flex items-center gap-2 text-xs">
                <label for="sort-select" class="text-zinc-500 hidden sm:inline whitespace-nowrap">Sắp xếp:</label>
                <select id="sort-select"
                        onchange="window.location.href = this.value"
                        class="border-zinc-300 text-xs py-2 px-3 focus:ring-0 focus:border-zinc-900 bg-white font-medium">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ $sort === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'bestselling']) }}" {{ $sort === 'bestselling' ? 'selected' : '' }}>Bán chạy nhất</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}" {{ $sort === 'featured' ? 'selected' : '' }}>Nổi bật</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Active Filters Tags (if any) -->
    @if(request()->hasAny(['category', 'brand', 'size', 'color', 'min_price', 'max_price', 'in_stock', 'filter', 'q']))
        <div class="flex items-center flex-wrap gap-2 pt-4">
            <span class="text-xs text-zinc-500">Đang lọc:</span>

            @if(request('category'))
                <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-zinc-100 text-zinc-800 text-xs hover:bg-zinc-200">
                    <span>Danh mục: {{ request('category') }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif

            @if(request('brand'))
                <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-zinc-100 text-zinc-800 text-xs hover:bg-zinc-200">
                    <span>Thương hiệu: {{ request('brand') }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif

            @if(request('size'))
                <a href="{{ request()->fullUrlWithQuery(['size' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-zinc-100 text-zinc-800 text-xs hover:bg-zinc-200">
                    <span>Size: {{ request('size') }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif

            @if(request('color'))
                <a href="{{ request()->fullUrlWithQuery(['color' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-zinc-100 text-zinc-800 text-xs hover:bg-zinc-200">
                    <span>Màu: {{ request('color') }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif

            @if(request('filter'))
                <a href="{{ request()->fullUrlWithQuery(['filter' => null]) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-zinc-100 text-zinc-800 text-xs hover:bg-zinc-200">
                    <span>Bộ sưu tập: {{ strtoupper(request('filter')) }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif

            <a href="{{ route('shop') }}" class="text-xs font-semibold text-rose-600 hover:underline ml-2">
                Xóa tất cả bộ lọc
            </a>
        </div>
    @endif

    <!-- Main Shop Layout: Sidebar + Grid -->
    <div class="pt-8 pb-16 flex gap-10">

        <!-- DESKTOP SIDEBAR FILTER -->
        <aside class="hidden lg:block w-64 flex-shrink-0 space-y-8">

            <!-- Categories Filter -->
            <div class="space-y-3 pb-6 border-b border-zinc-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-950">Danh Mục Sản Phẩm</h3>
                <ul class="space-y-2 text-xs">
                    <li>
                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
                           class="flex justify-between items-center py-1 {{ !request('category') ? 'font-bold text-zinc-950' : 'text-zinc-600 hover:text-black' }}">
                            <span>Tất cả danh mục</span>
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['category' => $cat->slug]) }}"
                               class="flex justify-between items-center py-1 {{ request('category') === $cat->slug ? 'font-bold text-zinc-950' : 'text-zinc-600 hover:text-black' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="text-zinc-400 text-[11px]">({{ $cat->products_count }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Brands Filter -->
            <div class="space-y-3 pb-6 border-b border-zinc-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-950">Thương Hiệu / Studio</h3>
                <ul class="space-y-2 text-xs">
                    @foreach($brands as $b)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['brand' => request('brand') === $b->slug ? null : $b->slug]) }}"
                               class="flex justify-between items-center py-1 {{ request('brand') === $b->slug ? 'font-bold text-zinc-950' : 'text-zinc-600 hover:text-black' }}">
                                <div class="flex items-center gap-2">
                                    <div class="w-3.5 h-3.5 border border-zinc-400 flex items-center justify-center {{ request('brand') === $b->slug ? 'bg-zinc-950 border-zinc-950' : '' }}">
                                        @if(request('brand') === $b->slug)
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </div>
                                    <span>{{ $b->name }}</span>
                                </div>
                                <span class="text-zinc-400 text-[11px]">({{ $b->products_count }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Size Filter -->
            <div class="space-y-3 pb-6 border-b border-zinc-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-950">Kích Cỡ (Size)</h3>
                <div class="grid grid-cols-4 gap-2 text-center text-xs">
                    @foreach($availableSizes as $s)
                        <a href="{{ request()->fullUrlWithQuery(['size' => request('size') === $s ? null : $s]) }}"
                           class="py-2 border text-xs font-medium uppercase transition-colors {{ request('size') === $s ? 'bg-zinc-950 text-white border-zinc-950' : 'border-zinc-200 text-zinc-800 hover:border-zinc-900' }}">
                            {{ $s }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Color Filter -->
            <div class="space-y-3 pb-6 border-b border-zinc-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-950">Màu Sắc</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($availableColors as $col)
                        <a href="{{ request()->fullUrlWithQuery(['color' => request('color') === $col['name'] ? null : $col['name']]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 border text-xs transition-colors {{ request('color') === $col['name'] ? 'border-zinc-900 bg-zinc-100 font-semibold' : 'border-zinc-200 hover:border-zinc-400' }}">
                            <span class="w-3 h-3 rounded-full border border-zinc-300" style="background-color: {{ $col['code'] }}"></span>
                            <span>{{ $col['name'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Price Filter -->
            <div class="space-y-3 pb-6 border-b border-zinc-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-950">Khoảng Giá</h3>
                <form action="{{ url()->current() }}" method="GET" class="space-y-3">
                    @foreach(request()->except(['min_price', 'max_price', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <div class="flex items-center gap-2 text-xs">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Từ" class="w-full text-xs p-2 border-zinc-300 focus:border-zinc-900 focus:ring-0" />
                        <span>-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Đến" class="w-full text-xs p-2 border-zinc-300 focus:border-zinc-900 focus:ring-0" />
                    </div>
                    <button type="submit" class="w-full py-2 bg-zinc-100 hover:bg-zinc-900 hover:text-white text-zinc-900 text-xs uppercase tracking-wider font-semibold transition-colors">
                        Áp Dụng
                    </button>
                </form>
            </div>

            <!-- Availability -->
            <div class="space-y-3">
                <label class="flex items-center gap-2.5 text-xs text-zinc-800 cursor-pointer">
                    <input type="checkbox"
                           {{ request()->boolean('in_stock') ? 'checked' : '' }}
                           onchange="window.location.href = '{{ request()->fullUrlWithQuery(['in_stock' => request()->boolean('in_stock') ? null : '1']) }}'"
                           class="w-4 h-4 text-zinc-900 border-zinc-300 focus:ring-0 rounded-none" />
                    <span>Chỉ hiển thị hàng có sẵn</span>
                </label>
            </div>
        </aside>

        <!-- PRODUCT GRID & PAGINATION -->
        <main class="flex-1">
            <x-product-grid :products="$products" :cols="3" />

            <div class="mt-8">
                <x-pagination :paginator="$products" />
            </div>
        </main>
    </div>

    <!-- MOBILE FILTER DRAWER -->
    <div x-show="mobileFilterOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 z-50 lg:hidden"
         @click="mobileFilterOpen = false"
         style="display: none;"></div>

    <div x-show="mobileFilterOpen"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 w-4/5 max-w-sm bg-white shadow-2xl z-50 overflow-y-auto p-6 flex flex-col justify-between lg:hidden"
         style="display: none;">

        <div class="space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
                <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">Bộ Lọc Sản Phẩm</h3>
                <button type="button" @click="mobileFilterOpen = false" class="text-zinc-500 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Mobile Categories -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold uppercase text-zinc-900">Danh Mục</h4>
                <div class="space-y-1.5 text-xs">
                    @foreach($categories as $c)
                        <a href="{{ request()->fullUrlWithQuery(['category' => $c->slug]) }}" class="block py-1 {{ request('category') === $c->slug ? 'font-bold text-zinc-950' : 'text-zinc-600' }}">
                            {{ $c->name }} ({{ $c->products_count }})
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Mobile Sizes -->
            <div class="space-y-2">
                <h4 class="text-xs font-bold uppercase text-zinc-900">Kích Cỡ</h4>
                <div class="grid grid-cols-4 gap-2 text-center text-xs">
                    @foreach($availableSizes as $s)
                        <a href="{{ request()->fullUrlWithQuery(['size' => request('size') === $s ? null : $s]) }}" class="py-2 border text-xs font-medium {{ request('size') === $s ? 'bg-zinc-900 text-white' : 'border-zinc-200' }}">
                            {{ $s }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Clear filters button -->
            <div class="pt-4 border-t border-zinc-200">
                <a href="{{ route('shop') }}" class="block w-full py-2.5 bg-zinc-100 text-zinc-900 text-center text-xs uppercase tracking-wider font-semibold">
                    Xóa Tất Cả Bộ Lọc
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
