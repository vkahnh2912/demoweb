@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Quản Lý Bài Viết Blog</h1>
            <p class="text-zinc-500 mt-1">Tin tức thời trang, phong cách và cẩm nang phong cách NOVAWEAR</p>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="px-4 py-2 bg-zinc-950 text-white font-bold uppercase tracking-wider hover:bg-black">
            + Viết Bài Mới
        </a>
    </div>

    <div class="bg-white border border-zinc-200 shadow-sm overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="p-3.5">Ảnh bài viết</th>
                    <th class="p-3.5">Tiêu đề bài viết</th>
                    <th class="p-3.5">Chuyên mục</th>
                    <th class="p-3.5">Tác giả</th>
                    <th class="p-3.5">Lượt xem</th>
                    <th class="p-3.5">Trạng thái</th>
                    <th class="p-3.5">Ngày đăng</th>
                    <th class="p-3.5 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse($blogs as $b)
                    <tr class="hover:bg-zinc-50">
                        <td class="p-3.5">
                            @if($b->image)
                                <img src="{{ asset('storage/' . $b->image) }}" class="w-16 h-12 object-cover border border-zinc-200 bg-zinc-100" />
                            @else
                                <div class="w-16 h-12 bg-zinc-100 flex items-center justify-center text-zinc-400 text-[10px]">No IMG</div>
                            @endif
                        </td>
                        <td class="p-3.5">
                            <a href="{{ route('blog.show', $b->slug) }}" target="_blank" class="font-bold text-zinc-900 hover:underline block max-w-sm">
                                {{ $b->title }}
                            </a>
                            <div class="text-[10px] text-zinc-400 font-mono mt-0.5">/blog/{{ $b->slug }}</div>
                        </td>
                        <td class="p-3.5 text-zinc-600 font-semibold">{{ $b->category->name ?? '—' }}</td>
                        <td class="p-3.5 text-zinc-500">{{ $b->author->name ?? 'Admin' }}</td>
                        <td class="p-3.5 font-bold text-zinc-700">{{ number_format($b->views) }}</td>
                        <td class="p-3.5">
                            @if($b->status === 'published')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">Đã xuất bản</span>
                            @else
                                <span class="px-2 py-0.5 bg-zinc-100 text-zinc-500 font-bold text-[10px]">Bản nháp</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-zinc-400 text-[11px]">{{ $b->created_at->format('d/m/Y') }}</td>
                        <td class="p-3.5 text-right space-x-2">
                            <a href="{{ route('admin.blogs.edit', $b) }}" class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-semibold uppercase text-[11px]">Sửa</a>
                            <form action="{{ route('admin.blogs.destroy', $b) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-rose-600 hover:text-rose-800 font-semibold uppercase text-[11px]">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-zinc-400">Chưa có bài viết blog nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $blogs->links() }}
    </div>
</div>
@endsection
