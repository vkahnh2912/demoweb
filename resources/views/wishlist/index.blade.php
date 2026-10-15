@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Danh Sách Yêu Thích']]" />

    <div class="py-4 border-b border-zinc-200 mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
            Danh Sách Yêu Thích Của Bạn ({{ $items->count() }})
        </h1>
    </div>

    @if($items->isEmpty())
        <div class="py-20 text-center space-y-4">
            <div class="w-20 h-20 bg-zinc-100 rounded-full flex items-center justify-center text-zinc-400 mx-auto">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-zinc-900">Danh sách yêu thích trống</h2>
            <p class="text-xs text-zinc-500 max-w-sm mx-auto">Hãy nhấn vào biểu tượng trái tim ở các sản phẩm bạn yêu thích để lưu lại và mua sau nhé.</p>
            <div class="pt-2">
                <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-8 py-3 bg-zinc-950 text-white text-xs font-bold uppercase tracking-wider hover:bg-zinc-800 transition-colors">
                    Khám Phá Cửa Hàng
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($items as $wishItem)
                <div class="relative group">
                    <x-product-card :product="$wishItem->product" />
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
