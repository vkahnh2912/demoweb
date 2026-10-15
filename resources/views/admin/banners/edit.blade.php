@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Sửa Banner: {{ $banner->title }}</h1>
        <a href="{{ route('admin.banners.index') }}" class="font-semibold text-zinc-600 hover:text-black">← Quay lại</a>
    </div>

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Tiêu đề Banner *</label>
            <input type="text" name="title" required value="{{ old('title', $banner->title) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            @error('title') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Phụ đề (Subtitle)</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Vị trí hiển thị *</label>
                <select name="position" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                    <option value="hero" {{ old('position', $banner->position) === 'hero' ? 'selected' : '' }}>Hero Slider (Đầu trang chủ)</option>
                    <option value="promo" {{ old('position', $banner->position) === 'promo' ? 'selected' : '' }}>Promotional (Khuyến mãi giữa trang)</option>
                    <option value="sidebar" {{ old('position', $banner->position) === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Thứ tự hiển thị (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Đường dẫn khi nhấp (Link URL)</label>
                <input type="text" name="link" value="{{ old('link', $banner->link) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Chữ hiển thị trên nút (Button Text)</label>
                <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        @if($banner->image)
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Ảnh hiện tại</label>
                <img src="{{ asset('storage/' . $banner->image) }}" class="w-48 h-24 object-cover border border-zinc-300" />
            </div>
        @endif

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Thay đổi tệp hình ảnh</label>
            <input type="file" name="image_file" accept="image/*" class="w-full text-xs p-2 border border-zinc-300 focus:border-zinc-950" />
        </div>

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Hoặc đường dẫn ảnh có sẵn (Path)</label>
            <input type="text" name="image" value="{{ old('image', $banner->image) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
        </div>

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Trạng thái *</label>
            <select name="status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                <option value="active" {{ old('status', $banner->status) === 'active' ? 'selected' : '' }}>Hiển thị (Active)</option>
                <option value="inactive" {{ old('status', $banner->status) === 'inactive' ? 'selected' : '' }}>Tạm ẩn (Inactive)</option>
            </select>
        </div>

        <div class="pt-4 border-t border-zinc-100 flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-zinc-950 text-white font-bold uppercase tracking-wider">Lưu Thay Đổi</button>
            <a href="{{ route('admin.banners.index') }}" class="px-6 py-2.5 bg-white border border-zinc-300 text-zinc-700 font-semibold uppercase">Hủy</a>
        </div>
    </form>
</div>
@endsection
