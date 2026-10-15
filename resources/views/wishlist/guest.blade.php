@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-20 text-center space-y-6">
    <div class="w-20 h-20 bg-zinc-100 rounded-full flex items-center justify-center text-zinc-400 mx-auto">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </div>
    <div class="space-y-2">
        <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-950">Danh Sách Yêu Thích</h1>
        <p class="text-xs text-zinc-500 leading-relaxed">
            Vui lòng đăng nhập vào tài khoản của bạn để lưu các sản phẩm yêu thích và đồng bộ trên tất cả các thiết bị.
        </p>
    </div>
    <div class="flex flex-col sm:flex-row gap-3 pt-2">
        <a href="{{ route('login') }}" class="flex-1 py-3 bg-zinc-950 text-white text-xs font-bold uppercase tracking-wider hover:bg-zinc-800 transition-colors">
            Đăng Nhập
        </a>
        <a href="{{ route('register') }}" class="flex-1 py-3 bg-white border border-zinc-900 text-zinc-900 text-xs font-bold uppercase tracking-wider hover:bg-zinc-100 transition-colors">
            Tạo Tài Khoản
        </a>
    </div>
</div>
@endsection
