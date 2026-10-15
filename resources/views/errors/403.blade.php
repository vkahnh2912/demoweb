@extends('layouts.app')

@section('title', '403 - Quyền Truy Cập Bị Từ Chối | NOVAWEAR')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-20 px-4 text-center">
    <div class="max-w-md mx-auto space-y-6">
        <span class="text-xs uppercase tracking-widest text-rose-500 font-mono font-semibold">Error 403</span>
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-zinc-950 uppercase">Truy Cập Bị Từ Chối</h1>
        <p class="text-xs sm:text-sm text-zinc-500 leading-relaxed">
            Bạn không có đặc quyền hoặc quyền hạn cần thiết để truy cập vào khu vực này.
        </p>
        <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-zinc-950 text-white font-bold uppercase tracking-wider text-xs hover:bg-black transition-colors">
                Trang Chủ
            </a>
            <a href="{{ route('login') }}" class="px-6 py-3 bg-white border border-zinc-300 text-zinc-800 font-bold uppercase tracking-wider text-xs hover:border-zinc-900 transition-colors">
                Đăng Nhập Khác
            </a>
        </div>
    </div>
</div>
@endsection
