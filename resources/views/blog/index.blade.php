@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'NOVAWEAR Journal']]" />

    <div class="py-6 border-b border-zinc-200 mb-10 text-center max-w-2xl mx-auto space-y-2">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-zinc-400">Editorial & Lifestyle</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-tight text-zinc-950">
            NOVAWEAR Journal
        </h1>
        <p class="text-xs text-zinc-500 leading-relaxed">
            Những góc nhìn thời trang, phong cách sống đương đại, hướng dẫn phối đồ và câu chuyện đằng sau các chất liệu dệt may thủ công.
        </p>
    </div>

    <!-- Category Pills Filter -->
    <div class="flex items-center justify-center flex-wrap gap-2 pb-10">
        <a href="{{ route('blog.index') }}"
           class="px-4 py-2 text-xs font-semibold uppercase tracking-wider transition-colors {{ !request('category') ? 'bg-zinc-950 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
            Tất Cả Bài Viết
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
               class="px-4 py-2 text-xs font-semibold uppercase tracking-wider transition-colors {{ request('category') === $cat->slug ? 'bg-zinc-950 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
                {{ $cat->name }} ({{ $cat->posts_count }})
            </a>
        @endforeach
    </div>

    <!-- Blog Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($blogs as $blog)
            <article class="group flex flex-col bg-white border border-zinc-100 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                <a href="{{ route('blog.show', $blog->slug) }}" class="block aspect-[16/10] bg-zinc-100 overflow-hidden">
                    <img src="{{ $blog->image_url }}"
                         alt="{{ $blog->title }}"
                         loading="lazy"
                         class="w-full h-full object-cover object-center transition-transform duration-700 ease-out group-hover:scale-105" />
                </a>

                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-[11px] text-zinc-400 uppercase tracking-wider">
                            <span>{{ $blog->category?->name ?? 'Thời trang' }}</span>
                            <span>{{ $blog->created_at->format('d/m/Y') }}</span>
                        </div>
                        <h2 class="text-base font-bold text-zinc-900 group-hover:text-zinc-600 transition-colors leading-snug line-clamp-2">
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                {{ $blog->title }}
                            </a>
                        </h2>
                        <p class="text-xs text-zinc-500 font-light leading-relaxed line-clamp-3">
                            {{ $blog->excerpt }}
                        </p>
                    </div>

                    <div class="pt-2 border-t border-zinc-100">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="text-xs font-bold uppercase tracking-wider text-zinc-900 group-hover:underline inline-flex items-center gap-1">
                            <span>Đọc Tiếp</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-12">
        <x-pagination :paginator="$blogs" />
    </div>
</div>
@endsection
