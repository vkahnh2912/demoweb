@extends('layouts.app')

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Journal', 'url' => route('blog.index')],
        ['label' => $blog->category?->name ?? 'Bài viết', 'url' => route('blog.index', ['category' => $blog->category?->slug])],
        ['label' => $blog->title]
    ]" />

    <header class="py-8 text-center space-y-4 max-w-2xl mx-auto">
        @if($blog->category)
            <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}" class="inline-block text-[11px] font-bold uppercase tracking-[0.25em] text-zinc-400 hover:text-zinc-950 transition-colors">
                {{ $blog->category->name }}
            </a>
        @endif

        <h1 class="text-2xl sm:text-4xl font-extrabold text-zinc-950 leading-tight">
            {{ $blog->title }}
        </h1>

        <div class="flex items-center justify-center gap-4 text-xs text-zinc-400">
            <span>Bởi: <strong class="text-zinc-700">{{ $blog->author?->name ?? 'NOVAWEAR Editorial' }}</strong></span>
            <span>•</span>
            <span>{{ $blog->created_at->format('d/m/Y') }}</span>
            <span>•</span>
            <span>{{ $blog->views }} lượt xem</span>
        </div>
    </header>

    <!-- Big Featured Cover Image -->
    <div class="aspect-[16/9] bg-zinc-100 overflow-hidden mb-10">
        <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover object-center" />
    </div>

    <!-- Article Content -->
    <div class="max-w-2xl mx-auto prose prose-zinc text-sm leading-relaxed space-y-6 text-zinc-700 font-light">
        <p class="text-base font-normal text-zinc-900 border-l-2 border-zinc-900 pl-4 italic">
            {{ $blog->excerpt }}
        </p>

        <div class="space-y-4">
            {!! $blog->content !!}
        </div>
    </div>

    <!-- Related Posts -->
    @if($relatedBlogs->isNotEmpty())
        <section class="mt-16 pt-12 border-t border-zinc-200">
            <h2 class="text-xl font-extrabold uppercase tracking-tight text-zinc-950 mb-8 text-center">
                Bài Viết Liên Quan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedBlogs as $rel)
                    <div class="group flex flex-col space-y-2">
                        <a href="{{ route('blog.show', $rel->slug) }}" class="block aspect-[16/10] bg-zinc-100 overflow-hidden">
                            <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover object-center transition-transform group-hover:scale-105 duration-500" />
                        </a>
                        <h3 class="text-xs font-bold text-zinc-900 line-clamp-2 group-hover:underline">
                            <a href="{{ route('blog.show', $rel->slug) }}">{{ $rel->title }}</a>
                        </h3>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</article>
@endsection
