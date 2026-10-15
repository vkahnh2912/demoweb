@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Sửa Bài Blog: {{ $blog->title }}</h1>
        <a href="{{ route('admin.blogs.index') }}" class="font-semibold text-zinc-600 hover:text-black">← Quay lại</a>
    </div>

    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 border border-zinc-200 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block font-semibold text-zinc-700 mb-1">Tiêu đề bài viết *</label>
                <input type="text" name="title" required value="{{ old('title', $blog->title) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                @error('title') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Chuyên mục *</label>
                <select name="category_id" required class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $blog->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Tóm tắt ngắn (Excerpt)</label>
            <textarea name="excerpt" rows="2" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('excerpt', $blog->excerpt) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Nội dung chi tiết bài viết *</label>
            <textarea name="content" rows="12" required class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 font-mono">{{ old('content', $blog->content) }}</textarea>
            @error('content') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
        </div>

        @if($blog->image)
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Ảnh hiện tại</label>
                <img src="{{ asset('storage/' . $blog->image) }}" class="w-40 h-28 object-cover border border-zinc-200" />
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Thay đổi ảnh đại diện</label>
                <input type="file" name="image_file" accept="image/*" class="w-full text-xs p-2 border border-zinc-300 focus:border-zinc-950" />
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Hoặc đường dẫn ảnh có sẵn</label>
                <input type="text" name="image" value="{{ old('image', $blog->image) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3 border-t border-zinc-100">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Trạng thái bài viết *</label>
                <select name="status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                    <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Xuất bản (Published)</option>
                    <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">SEO Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">SEO Meta Description</label>
                <input type="text" name="meta_description" value="{{ old('meta_description', $blog->meta_description) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        <div class="pt-4 border-t border-zinc-100 flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-zinc-950 text-white font-bold uppercase tracking-wider">Lưu Cập Nhật</button>
            <a href="{{ route('admin.blogs.index') }}" class="px-6 py-2.5 bg-white border border-zinc-300 text-zinc-700 font-semibold uppercase">Hủy</a>
        </div>
    </form>
</div>
@endsection
