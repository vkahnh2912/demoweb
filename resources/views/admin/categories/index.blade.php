@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Danh Mục Sản Phẩm</h1>
            <p class="text-xs text-zinc-500">Quản lý các danh mục thời trang</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="px-5 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs uppercase font-bold tracking-wider transition-colors">
            + Thêm Danh Mục Mới
        </a>
    </div>

    <div class="bg-white border border-zinc-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 uppercase text-[11px]">
                <tr>
                    <th class="py-3 px-6">Ảnh</th>
                    <th class="py-3 px-6">Tên danh mục</th>
                    <th class="py-3 px-6">Slug</th>
                    <th class="py-3 px-6">Số sản phẩm</th>
                    <th class="py-3 px-6">Trạng thái</th>
                    <th class="py-3 px-6 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @foreach($categories as $c)
                    <tr class="hover:bg-zinc-50/50">
                        <td class="py-3 px-6">
                            @if($c->image)
                                <img src="{{ $c->image }}" alt="{{ $c->name }}" class="w-10 h-10 object-cover bg-zinc-100 rounded-none" />
                            @else
                                <div class="w-10 h-10 bg-zinc-100 flex items-center justify-center text-zinc-400">—</div>
                            @endif
                        </td>
                        <td class="py-3 px-6 font-bold text-zinc-900">{{ $c->name }}</td>
                        <td class="py-3 px-6 font-mono text-zinc-500">{{ $c->slug }}</td>
                        <td class="py-3 px-6 font-semibold text-zinc-800">{{ $c->products_count }} sản phẩm</td>
                        <td class="py-3 px-6">
                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase {{ $c->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-zinc-100 text-zinc-600' }}">
                                {{ $c->status === 'active' ? 'Hoạt động' : 'Tạm ẩn' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-right space-x-2">
                            <a href="{{ route('admin.categories.edit', $c->id) }}" class="font-bold text-zinc-900 hover:underline">Sửa</a>
                            <form action="{{ route('admin.categories.destroy', $c->id) }}" method="POST" class="inline" onsubmit="return confirm('Xóa danh mục này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-bold text-rose-600 hover:underline">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
