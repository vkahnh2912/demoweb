@extends('layouts.app')

@section('content')
<div class="space-y-16 sm:space-y-24 pb-16">

    <!-- 1. HERO BANNER SECTION -->
    <section class="relative bg-zinc-100 overflow-hidden" x-data="{
        currentSlide: 0,
        slidesCount: {{ $heroBanners->count() ?: 1 }},
        autoSlide() {
            setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.slidesCount;
            }, 6000);
        }
    }" x-init="autoSlide()">
        <div class="relative w-full h-[520px] sm:h-[650px] lg:h-[750px]">
            @forelse($heroBanners as $idx => $banner)
                <div x-show="currentSlide === {{ $idx }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-700"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0"
                     style="display: none;">

                    <img src="{{ $banner->image_url }}"
                         alt="{{ $banner->title }}"
                         class="w-full h-full object-cover object-center" />

                    <!-- Dark Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent flex items-center">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                            <div class="max-w-xl space-y-4 text-white">
                                <span class="inline-block text-[11px] font-bold uppercase tracking-[0.3em] text-zinc-300">
                                    Bộ Sưu Tập Mới 2026
                                </span>
                                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight uppercase leading-tight">
                                    {{ $banner->title }}
                                </h1>
                                @if($banner->subtitle)
                                    <p class="text-xs sm:text-sm text-zinc-200 leading-relaxed font-light max-w-md">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif
                                <div class="pt-4">
                                    <a href="{{ $banner->link ?: route('shop') }}"
                                       class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-zinc-950 text-xs font-bold uppercase tracking-[0.2em] hover:bg-zinc-200 transition-colors shadow-xl">
                                        {{ $banner->button_text ?: 'Khám Phá Ngay' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="w-full h-full bg-zinc-900 flex items-center justify-center text-white">
                    <div class="text-center space-y-3">
                        <h1 class="text-4xl font-extrabold tracking-widest uppercase">NOVAWEAR</h1>
                        <p class="text-xs text-zinc-400">Contemporary Fashion Collection</p>
                        <a href="{{ route('shop') }}" class="inline-block mt-4 px-6 py-2.5 bg-white text-black text-xs uppercase tracking-wider font-semibold">Khám Phá</a>
                    </div>
                </div>
            @endforelse

            <!-- Slider Dots -->
            @if($heroBanners->count() > 1)
                <div class="absolute bottom-6 inset-x-0 flex items-center justify-center gap-2 z-20">
                    @foreach($heroBanners as $idx => $b)
                        <button type="button"
                                @click="currentSlide = {{ $idx }}"
                                class="w-8 h-1 transition-all duration-300"
                                :class="currentSlide === {{ $idx }} ? 'bg-white' : 'bg-white/40 hover:bg-white/70'"
                                aria-label="Slide {{ $idx + 1 }}">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- 2. VALUE PROPOSITIONS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-8 border-y border-zinc-100 text-center">
            <div class="flex flex-col items-center space-y-2">
                <svg class="w-6 h-6 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900">Miễn Phí Giao Hàng</h4>
                <p class="text-[11px] text-zinc-500">Đơn hàng từ 500.000₫ toàn quốc</p>
            </div>
            <div class="flex flex-col items-center space-y-2">
                <svg class="w-6 h-6 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900">Đổi Trả Dễ Dàng</h4>
                <p class="text-[11px] text-zinc-500">Hỗ trợ đổi size tận nhà trong 7 ngày</p>
            </div>
            <div class="flex flex-col items-center space-y-2">
                <svg class="w-6 h-6 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900">Vải Sợi Tự Nhiên</h4>
                <p class="text-[11px] text-zinc-500">Cotton 100%, Linen, Wool cao cấp</p>
            </div>
            <div class="flex flex-col items-center space-y-2">
                <svg class="w-6 h-6 text-zinc-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-900">Thanh Toán An Toàn</h4>
                <p class="text-[11px] text-zinc-500">Nhận hàng kiểm tra thanh toán COD</p>
            </div>
        </div>
    </section>

    <!-- 3. FEATURED CATEGORIES -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-10 space-y-2">
            <span class="text-[11px] font-bold uppercase tracking-[0.25em] text-zinc-400">Danh Mục Nổi Bật</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">Khám Phá Theo Phong Cách</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            @foreach($featuredCategories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="group relative block aspect-[3/4] bg-zinc-100 overflow-hidden">
                    <img src="{{ $category->image }}"
                         alt="{{ $category->name }}"
                         loading="lazy"
                         class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-108" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent flex flex-col justify-end p-5 text-white">
                        <h3 class="text-sm sm:text-base font-bold uppercase tracking-wider group-hover:translate-x-1 transition-transform">
                            {{ $category->name }}
                        </h3>
                        <span class="text-[11px] text-zinc-300 font-light mt-0.5 group-hover:text-white transition-colors">
                            Xem bộ sưu tập →
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 4. NEW ARRIVALS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 pb-4 border-b border-zinc-200 gap-4">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-[0.25em] text-zinc-400">Được May Đo Mới Nhất</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950 mt-1">Hàng Mới Về</h2>
            </div>
            <a href="{{ route('shop', ['filter' => 'new']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-zinc-600 transition-colors inline-flex items-center gap-1">
                <span>Xem Tất Cả</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <x-product-grid :products="$newArrivals" :cols="4" />
    </section>

    <!-- 5. PROMOTIONAL BANNER -->
    @if($promoBanner)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-zinc-900 overflow-hidden aspect-[16/8] sm:aspect-[21/8]">
                <img src="{{ $promoBanner->image_url }}"
                     alt="{{ $promoBanner->title }}"
                     class="w-full h-full object-cover object-center opacity-70" />

                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent flex items-center p-6 sm:p-12 lg:p-16">
                    <div class="max-w-lg space-y-3 sm:space-y-4 text-white">
                        <span class="inline-block px-3 py-1 bg-rose-600 text-white text-[10px] font-bold uppercase tracking-wider">
                            ƯU ĐÃI GIỚI HẠN
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-extrabold uppercase tracking-tight leading-tight">
                            {{ $promoBanner->title }}
                        </h2>
                        @if($promoBanner->subtitle)
                            <p class="text-xs sm:text-sm text-zinc-300 font-light leading-relaxed hidden sm:block">
                                {{ $promoBanner->subtitle }}
                            </p>
                        @endif
                        <div class="pt-2">
                            <a href="{{ $promoBanner->link ?: route('shop') }}"
                               class="inline-flex items-center justify-center px-7 py-3 bg-white text-zinc-950 text-xs font-bold uppercase tracking-wider hover:bg-zinc-200 transition-colors shadow-lg">
                                {{ $promoBanner->button_text ?: 'Mua Ngay' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- 6. BEST SELLERS -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 pb-4 border-b border-zinc-200 gap-4">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-[0.25em] text-zinc-400">Được Yêu Thích Nhất</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950 mt-1">Sản Phẩm Bán Chạy</h2>
            </div>
            <a href="{{ route('shop', ['filter' => 'bestseller']) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-zinc-600 transition-colors inline-flex items-center gap-1">
                <span>Xem Thêm</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <x-product-grid :products="$bestSellers" :cols="4" />
    </section>

    <!-- 7. BRAND STUDIOS SECTION -->
    <section class="bg-zinc-50 py-16 border-y border-zinc-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-10 space-y-2">
                <span class="text-[11px] font-bold uppercase tracking-[0.25em] text-zinc-400">Hệ Sinh Thái Sáng Tạo</span>
                <h2 class="text-2xl font-extrabold uppercase tracking-tight text-zinc-900">Các Studio & Thương Hiệu Đồng Hành</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-center">
                @foreach($brands as $brand)
                    <a href="{{ route('shop', ['brand' => $brand->slug]) }}"
                       class="p-6 bg-white border border-zinc-100 hover:border-zinc-900 transition-all duration-300 flex flex-col items-center justify-center group shadow-sm hover:shadow-md">
                        <span class="text-sm font-bold uppercase tracking-widest text-zinc-800 group-hover:text-black">
                            {{ $brand->name }}
                        </span>
                        <span class="text-[10px] text-zinc-400 mt-1 group-hover:text-zinc-600">Khám phá sản phẩm</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 8. BLOG / JOURNAL -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 pb-4 border-b border-zinc-200 gap-4">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-[0.25em] text-zinc-400">Góc Cảm Hứng & Phong Cách</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950 mt-1">NOVAWEAR Journal</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:text-zinc-600 transition-colors inline-flex items-center gap-1">
                <span>Đọc Thêm Bài Viết</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($latestBlogs as $blog)
                <article class="group flex flex-col space-y-3">
                    <a href="{{ route('blog.show', $blog->slug) }}" class="block aspect-[16/10] bg-zinc-100 overflow-hidden">
                        <img src="{{ $blog->image_url }}"
                             alt="{{ $blog->title }}"
                             loading="lazy"
                             class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105" />
                    </a>
                    <div class="space-y-1.5 flex-grow">
                        @if($blog->category)
                            <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">
                                {{ $blog->category->name }}
                            </span>
                        @endif
                        <h3 class="text-base font-bold text-zinc-900 group-hover:text-zinc-600 transition-colors leading-snug line-clamp-2">
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                {{ $blog->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-zinc-500 line-clamp-2 font-light leading-relaxed">
                            {{ $blog->excerpt }}
                        </p>
                    </div>
                    <div class="pt-1">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 hover:underline">
                            Đọc tiếp →
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

</div>
@endsection
