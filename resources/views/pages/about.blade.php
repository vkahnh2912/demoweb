@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 space-y-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Về Chúng Tôi']]" />

    <header class="text-center space-y-3">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-zinc-400">Câu Chuyện Thương Hiệu</span>
        <h1 class="text-3xl sm:text-5xl font-extrabold uppercase tracking-tight text-zinc-950">
            Về NOVAWEAR
        </h1>
        <p class="text-xs sm:text-sm text-zinc-500 max-w-xl mx-auto">
            Thời trang đương đại tôn vinh sự tinh tế, phom dáng chuẩn mực và lối sống bền vững.
        </p>
    </header>

    <div class="aspect-[16/8] bg-zinc-100 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&w=1600&q=80" alt="NOVAWEAR Studio" class="w-full h-full object-cover" />
    </div>

    <div class="prose prose-zinc max-w-none text-xs sm:text-sm leading-relaxed text-zinc-600 space-y-6">
        <h2 class="text-xl font-bold uppercase tracking-tight text-zinc-950">Triết Lý Thiết Kế Tối Giản</h2>
        <p>
            Được thành lập với sứ mệnh định hình lại tủ đồ của người trẻ Việt, <strong>NOVAWEAR</strong> không chạy theo các xu hướng sớm nở tối tàn. Chúng tôi tin vào những món đồ có thể đồng hành cùng bạn qua nhiều năm tháng: từ những chiếc áo sơ mi oxford chỉn chu, áo thun cotton 100% 260gsm đứng phom, cho đến các mẫu blazer tối giản thanh lịch.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-4 text-center">
            <div class="p-6 bg-zinc-50 border border-zinc-100 space-y-2">
                <span class="text-2xl font-extrabold text-zinc-950">100%</span>
                <h3 class="text-xs font-bold uppercase text-zinc-900">Sợi Tự Nhiên</h3>
                <p class="text-[11px] text-zinc-500">Ưu tiên cotton hữu cơ, đũi linen và len tự nhiên.</p>
            </div>
            <div class="p-6 bg-zinc-50 border border-zinc-100 space-y-2">
                <span class="text-2xl font-extrabold text-zinc-950">07 Ngày</span>
                <h3 class="text-xs font-bold uppercase text-zinc-900">Đổi Trả Tận Nhà</h3>
                <p class="text-[11px] text-zinc-500">Quy trình đổi hàng nhanh chóng và tận tâm.</p>
            </div>
            <div class="p-6 bg-zinc-50 border border-zinc-100 space-y-2">
                <span class="text-2xl font-extrabold text-zinc-950">63 Tỉnh</span>
                <h3 class="text-xs font-bold uppercase text-zinc-900">Giao Hàng Toàn Quốc</h3>
                <p class="text-[11px] text-zinc-500">Đồng kiểm tra hàng trước khi thanh toán COD.</p>
            </div>
        </div>
    </div>
</div>
@endsection
