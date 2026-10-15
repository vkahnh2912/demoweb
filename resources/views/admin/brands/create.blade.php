@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Thêm Thương Hiệu Mới</h1>
        <a href="{{ route('admin.brands.index') }}" class="font-semibold text-zinc-600 hover:text-black">← Quay lại</a>
    </div>

    <form action="{{ route('admin.brands.store') }}" method="POST" class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
        @csrf
        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Tên thương hiệu / Studio *</label>
            <input type="text" name="name" required value="{{ old('name') }}" placeholder="Ví dụ: NOVA Studio" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            @error('name') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Mô tả thương hiệu</label>
            <textarea name="description" rows="3" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Trạng thái *</label>
            <select name="status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                <option value="active">Hoạt động (Active)</option>
                <option value="inactive">Ẩn (Inactive)</option>
            </select>
        </div>
        <div class="pt-2">
            <label class="flex items-center gap-2 cursor-pointer font-semibold text-zinc-800">
                <input type="checkbox" name="is_featured" value="1" checked class="w-4 h-4 text-zinc-950 focus:ring-0" />
                <span>Nổi bật trên trang chủ (Featured)</span>
            </label>
        </div>
        <div class="pt-4 border-t border-zinc-100 flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-zinc-950 text-white font-bold uppercase tracking-wider">Lưu Thương Hiệu</button>
            <a href="{{ route('admin.brands.index') }}" class="px-6 py-2.5 bg-white border border-zinc-300 text-zinc-700 font-semibold uppercase">Hủy</a>
        </div>
    </form>
</div>
@endsection
