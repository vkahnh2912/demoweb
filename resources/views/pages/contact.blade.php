@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 space-y-12">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[['label' => 'Liên Hệ']]" />

    <header class="text-center space-y-2 max-w-xl mx-auto">
        <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-zinc-400">Kết Nối Với Chúng Tôi</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold uppercase tracking-tight text-zinc-950">
            Liên Hệ NOVAWEAR
        </h1>
        <p class="text-xs text-zinc-500">
            Mọi thắc mắc về đơn hàng, đổi size hoặc hợp tác phát triển, đừng ngần ngại gửi tin nhắn cho chúng tôi.
        </p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <!-- Contact Info (5 cols) -->
        <div class="lg:col-span-5 space-y-6 text-xs text-zinc-600 bg-zinc-50 p-6 sm:p-8 border border-zinc-200">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 pb-2 border-b border-zinc-200">
                Thông Tin Trụ Sở
            </h2>

            <div class="space-y-4">
                <div>
                    <strong class="text-zinc-900 block font-bold mb-1">Cửa Hàng Flagship:</strong>
                    <p>68 Nguyễn Huệ, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh</p>
                    <p class="text-zinc-400 text-[11px] mt-0.5">Mở cửa: 9:00 - 22:00 hàng ngày</p>
                </div>

                <div>
                    <strong class="text-zinc-900 block font-bold mb-1">Hotline CSKH:</strong>
                    <p class="font-mono text-zinc-900 font-bold text-sm">1900 888 999</p>
                    <p class="text-zinc-400 text-[11px] mt-0.5">Hỗ trợ từ 8:30 đến 21:30</p>
                </div>

                <div>
                    <strong class="text-zinc-900 block font-bold mb-1">Email:</strong>
                    <p>contact@novawear.vn</p>
                </div>
            </div>
        </div>

        <!-- Contact Form (7 cols) -->
        <div class="lg:col-span-7">
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Họ và tên *</label>
                    <input type="text" name="name" required class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" placeholder="Nguyễn Văn A" />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-zinc-700 mb-1">Địa chỉ email *</label>
                        <input type="email" name="email" required class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" placeholder="email@example.com" />
                    </div>
                    <div>
                        <label class="block font-semibold text-zinc-700 mb-1">Số điện thoại</label>
                        <input type="tel" name="phone" class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" placeholder="0912 345 678" />
                    </div>
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Nội dung tin nhắn *</label>
                    <textarea name="message" rows="5" required class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0" placeholder="Nhập câu hỏi hoặc yêu cầu cần tư vấn..."></textarea>
                </div>
                <button type="submit" class="px-8 py-3.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-bold uppercase tracking-wider transition-colors">
                    Gửi Tin Nhắn
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
