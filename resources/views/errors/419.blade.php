@extends('layouts.app')

@section('title', '419 - Phiên Làm Việc Hết Hạn | NOVAWEAR')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-20 px-4 text-center">
    <div class="max-w-md mx-auto space-y-6">
        <span class="text-xs uppercase tracking-widest text-amber-500 font-mono font-semibold">Error 419</span>
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-zinc-950 uppercase">Phiên Hết Hạn</h1>
        <p class="text-xs sm:text-sm text-zinc-500 leading-relaxed">
            Phiên làm việc bảo mật CSRF của bạn đã hết hạn do không thao tác trong thời gian dài. Vui lòng tải lại trang và thử lại.
        </p>
        <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <button onclick="window.location.reload();" class="px-6 py-3 bg-zinc-950 text-white font-bold uppercase tracking-wider text-xs hover:bg-black transition-colors">
                Tải Lại Trang
            </button>
            <a href="{{ route('home') }}" class="px-6 py-3 bg-white border border-zinc-300 text-zinc-800 font-bold uppercase tracking-wider text-xs hover:border-zinc-900 transition-colors">
                Về Trang Chủ
            </a>
        </div>
    </div>
</div>
@endsection
