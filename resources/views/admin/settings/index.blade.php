@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 text-xs">
    <div class="pb-4 border-b border-zinc-200">
        <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Cấu Hình Hệ Thống (Settings)</h1>
        <p class="text-zinc-500 mt-1">Quản lý thông tin liên hệ, phí vận chuyển và liên kết mạng xã hội của NOVAWEAR</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white p-6 border border-zinc-200 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <!-- General Info -->
        <div class="space-y-4">
            <h2 class="font-bold uppercase tracking-wider text-zinc-800 pb-2 border-b border-zinc-100">Thông Tin Thương Hiệu</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Tên website / Thương hiệu</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'NOVAWEAR') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Khẩu hiệu (Tagline)</label>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Email hỗ trợ khách hàng</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Hotline / Số điện thoại</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $settings['site_phone'] ?? '') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
            </div>

            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Địa chỉ cửa hàng / Trụ sở chính</label>
                <input type="text" name="site_address" value="{{ old('site_address', $settings['site_address'] ?? '') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        <!-- Shipping Config -->
        <div class="space-y-4 pt-4 border-t border-zinc-100">
            <h2 class="font-bold uppercase tracking-wider text-zinc-800 pb-2 border-b border-zinc-100">Cấu Hình Vận Chuyển</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Phí vận chuyển mặc định (VNĐ)</label>
                    <input type="number" name="shipping_fee" value="{{ old('shipping_fee', $settings['shipping_fee'] ?? '30000') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Đơn hàng tối thiểu để Miễn phí ship (VNĐ)</label>
                    <input type="number" name="free_shipping_min" value="{{ old('free_shipping_min', $settings['free_shipping_min'] ?? '500000') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="space-y-4 pt-4 border-t border-zinc-100">
            <h2 class="font-bold uppercase tracking-wider text-zinc-800 pb-2 border-b border-zinc-100">Mạng Xã Hội</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Facebook URL</label>
                    <input type="url" name="facebook" value="{{ old('facebook', $settings['facebook'] ?? '') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Instagram URL</label>
                    <input type="url" name="instagram" value="{{ old('instagram', $settings['instagram'] ?? '') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-zinc-100 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-zinc-950 text-white font-bold uppercase tracking-wider hover:bg-black">
                Lưu Cấu Hình
            </button>
        </div>
    </form>
</div>
@endsection
