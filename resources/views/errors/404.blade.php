@extends('layouts.app')

@section('title', '404 - Không Tìm Thấy Trang | NOVAWEAR')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-20 px-4 text-center">
    <div class="max-w-md mx-auto space-y-6">
        <span class="text-xs uppercase tracking-widest text-zinc-400 font-mono font-semibold">Error 404</span>
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-zinc-950 uppercase">Trang Không Tồn Tại</h1>
        <p class="text-xs sm:text-sm text-zinc-500 leading-relaxed">
            Rất tiếc, liên kết bạn vừa truy cập có thể đã bị xóa, thay đổi địa chỉ hoặc tạm thời không khả dụng.
        </p>
        <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-zinc-950 text-white font-bold uppercase tracking-wider text-xs hover:bg-black transition-colors">
                Trang Chủ
            </a>
            <a href="{{ route('shop') }}" class="px-6 py-3 bg-white border border-zinc-300 text-zinc-800 font-bold uppercase tracking-wider text-xs hover:border-zinc-900 transition-colors">
                Xem Cửa Hàng
            </a>
        </div>
    </div>
</div>
@endsection
