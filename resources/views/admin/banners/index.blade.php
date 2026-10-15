@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Quản Lý Banner Quảng Cáo</h1>
            <p class="text-zinc-500 mt-1">Quản lý banner slide trang chủ và banner khuyến mãi</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="px-4 py-2 bg-zinc-950 text-white font-bold uppercase tracking-wider hover:bg-black">
            + Thêm Banner Mới
        </a>
    </div>

    <div class="bg-white border border-zinc-200 shadow-sm overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="p-3.5">Hình ảnh</th>
                    <th class="p-3.5">Tiêu đề & Nội dung</th>
                    <th class="p-3.5">Vị trí</th>
                    <th class="p-3.5">Nút bấm & Liên kết</th>
                    <th class="p-3.5">Thứ tự</th>
                    <th class="p-3.5">Trạng thái</th>
                    <th class="p-3.5 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse($banners as $b)
                    <tr class="hover:bg-zinc-50">
                        <td class="p-3.5">
                            @if($b->image)
                                <img src="{{ asset('storage/' . $b->image) }}" class="w-24 h-12 object-cover border border-zinc-200 bg-zinc-100" />
                            @else
                                <div class="w-24 h-12 bg-zinc-100 flex items-center justify-center text-zinc-400 text-[10px]">No IMG</div>
                            @endif
                        </td>
                        <td class="p-3.5">
                            <div class="font-bold text-zinc-900">{{ $b->title }}</div>
                            <div class="text-[11px] text-zinc-500">{{ $b->subtitle ?? '—' }}</div>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 bg-zinc-100 font-bold uppercase text-[10px] text-zinc-800">
                                {{ $b->position }}
                            </span>
                        </td>
                        <td class="p-3.5 text-zinc-600">
                            <div>{{ $b->button_text ?? '—' }}</div>
                            <div class="text-[10px] text-zinc-400 truncate max-w-xs">{{ $b->link ?? '—' }}</div>
                        </td>
                        <td class="p-3.5 font-bold text-zinc-700">{{ $b->sort_order }}</td>
                        <td class="p-3.5">
                            @if($b->status === 'active')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">Hiển thị</span>
                            @else
                                <span class="px-2 py-0.5 bg-zinc-100 text-zinc-500 font-bold text-[10px]">Ẩn</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right space-x-2">
                            <a href="{{ route('admin.banners.edit', $b) }}" class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-semibold uppercase text-[11px]">Sửa</a>
                            <form action="{{ route('admin.banners.destroy', $b) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa banner này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-rose-600 hover:text-rose-800 font-semibold uppercase text-[11px]">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-zinc-400">Chưa có banner nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $banners->links() }}
    </div>
</div>
@endsection
