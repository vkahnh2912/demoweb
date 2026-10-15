@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Viết Bài Blog Mới</h1>
        <a href="{{ route('admin.blogs.index') }}" class="font-semibold text-zinc-600 hover:text-black">← Quay lại</a>
    </div>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 border border-zinc-200 shadow-sm space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-semibold text-zinc-700 mb-1">Tiêu đề bài viết *</label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="VD: Xu Hướng Thời Trang Tối Giản 2026..." class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                @error('title') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Chuyên mục *</label>
                <select name="category_id" required class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                    <option value="">-- Chọn chuyên mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Tóm tắt ngắn (Excerpt)</label>
            <textarea name="excerpt" rows="2" placeholder="Một vài dòng tóm tắt xuất hiện trên danh sách blog..." class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('excerpt') }}</textarea>
        </div>

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Nội dung chi tiết bài viết (HTML / Markdown) *</label>
            <textarea name="content" rows="12" required placeholder="Nhập nội dung bài viết ở đây..." class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 font-mono">{{ old('content') }}</textarea>
            @error('content') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Tải ảnh đại diện bài viết</label>
                <input type="file" name="image_file" accept="image/*" class="w-full text-xs p-2 border border-zinc-300 focus:border-zinc-950" />
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Hoặc đường dẫn ảnh có sẵn</label>
                <input type="text" name="image" value="{{ old('image') }}" placeholder="blogs/post-1.jpg" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-zinc-100">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Trạng thái bài viết *</label>
                <select name="status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                    <option value="published">Xuất bản ngay (Published)</option>
                    <option value="draft">Bản nháp (Draft)</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">SEO Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Để trống lấy theo tiêu đề" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">SEO Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description') }}" placeholder="Mô tả tìm kiếm Google" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        <div class="pt-4 border-t border-zinc-100 flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-zinc-950 text-white font-bold uppercase tracking-wider">Xuất Bản Bài Viết</button>
            <a href="{{ route('admin.blogs.index') }}" class="px-6 py-2.5 bg-white border border-zinc-300 text-zinc-700 font-semibold uppercase">Hủy</a>
        </div>
    </form>
</div>
@endsection
