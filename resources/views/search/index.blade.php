@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Tìm Kiếm']
    ]" />

    <div class="py-6 border-b border-zinc-200 mb-8 space-y-4">
        <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
            Kết Quả Tìm Kiếm: <span class="text-zinc-600 font-normal">"{{ $q }}"</span>
        </h1>
        <p class="text-xs text-zinc-500">
            Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm phù hợp với từ khóa của bạn.
        </p>

        <!-- Big Search Bar -->
        <form action="{{ route('search') }}" method="GET" class="max-w-xl flex gap-2 pt-2">
            <input type="text"
                   name="q"
                   value="{{ $q }}"
                   placeholder="Tìm kiếm sản phẩm khác..."
                   class="flex-1 text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            <button type="submit" class="px-6 py-3 bg-zinc-950 text-white text-xs uppercase font-bold tracking-wider hover:bg-zinc-800 transition-colors">
                Tìm Kiếm
            </button>
        </form>
    </div>

    <!-- Results Grid -->
    <x-product-grid :products="$products" :cols="4" />

    <div class="mt-10">
        <x-pagination :paginator="$products" />
    </div>
</div>
@endsection
