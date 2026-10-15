@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Thương Hiệu / Studio ({{ $brands->total() }})</h1>
            <p class="text-xs text-zinc-500">Quản lý các thương hiệu may đo đối tác</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="px-5 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs uppercase font-bold tracking-wider transition-colors">
            + Thêm Thương Hiệu Mới
        </a>
    </div>

    <div class="bg-white border border-zinc-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 uppercase text-[11px]">
                <tr>
                    <th class="py-3 px-6">Tên thương hiệu</th>
                    <th class="py-3 px-6">Slug</th>
                    <th class="py-3 px-6">Mô tả</th>
                    <th class="py-3 px-6">Số sản phẩm</th>
                    <th class="py-3 px-6">Trạng thái</th>
                    <th class="py-3 px-6 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @foreach($brands as $b)
                    <tr class="hover:bg-zinc-50/50">
                        <td class="py-3 px-6 font-bold text-zinc-900">{{ $b->name }}</td>
                        <td class="py-3 px-6 font-mono text-zinc-500">{{ $b->slug }}</td>
                        <td class="py-3 px-6 text-zinc-600 max-w-xs truncate">{{ $b->description ?? '—' }}</td>
                        <td class="py-3 px-6 font-semibold text-zinc-800">{{ $b->products_count }} sản phẩm</td>
                        <td class="py-3 px-6">
                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase {{ $b->status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-zinc-100 text-zinc-600' }}">
                                {{ $b->status === 'active' ? 'Hoạt động' : 'Tạm ẩn' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-right space-x-2">
                            <a href="{{ route('admin.brands.edit', $b->id) }}" class="font-bold text-zinc-900 hover:underline">Sửa</a>
                            <form action="{{ route('admin.brands.destroy', $b->id) }}" method="POST" class="inline" onsubmit="return confirm('Xóa thương hiệu này?');">
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
