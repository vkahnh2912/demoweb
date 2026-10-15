@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Quản Lý Đánh Giá (Reviews)</h1>
            <p class="text-zinc-500 mt-1">Duyệt hoặc từ chối phản hồi, đánh giá từ khách hàng đã mua sản phẩm</p>
        </div>

        <!-- Filter Status -->
        <div class="flex gap-2">
            <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1.5 border {{ !request('status') ? 'bg-zinc-950 text-white font-bold' : 'bg-white text-zinc-600' }}">Tất cả</a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-3 py-1.5 border {{ request('status') === 'pending' ? 'bg-zinc-950 text-white font-bold' : 'bg-white text-zinc-600' }}">Chờ duyệt</a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-3 py-1.5 border {{ request('status') === 'approved' ? 'bg-zinc-950 text-white font-bold' : 'bg-white text-zinc-600' }}">Đã duyệt</a>
            <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" class="px-3 py-1.5 border {{ request('status') === 'rejected' ? 'bg-zinc-950 text-white font-bold' : 'bg-white text-zinc-600' }}">Từ chối</a>
        </div>
    </div>

    <div class="bg-white border border-zinc-200 shadow-sm overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="p-3.5">Sản phẩm</th>
                    <th class="p-3.5">Khách hàng</th>
                    <th class="p-3.5">Đánh giá sao</th>
                    <th class="p-3.5">Nội dung nhận xét</th>
                    <th class="p-3.5">Trạng thái</th>
                    <th class="p-3.5">Thời gian</th>
                    <th class="p-3.5 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse($reviews as $rev)
                    <tr class="hover:bg-zinc-50">
                        <td class="p-3.5">
                            @if($rev->product)
                                <a href="{{ route('products.show', $rev->product->slug) }}" target="_blank" class="font-semibold text-zinc-900 hover:underline block max-w-xs truncate">
                                    {{ $rev->product->name }}
                                </a>
                            @else
                                <span class="italic text-zinc-400">Sản phẩm đã bị xóa</span>
                            @endif
                        </td>
                        <td class="p-3.5">
                            <div class="font-semibold text-zinc-800">{{ $rev->user->name ?? 'Người dùng' }}</div>
                            <div class="text-[10px] text-zinc-400">{{ $rev->user->email ?? '' }}</div>
                        </td>
                        <td class="p-3.5">
                            <div class="flex text-amber-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </td>
                        <td class="p-3.5 text-zinc-700 max-w-md">
                            <p class="line-clamp-2">{{ $rev->comment }}</p>
                        </td>
                        <td class="p-3.5">
                            @if($rev->status === 'approved')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">Đã duyệt</span>
                            @elseif($rev->status === 'pending')
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-bold text-[10px] border border-amber-200">Chờ duyệt</span>
                            @else
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-700 font-bold text-[10px] border border-rose-200">Từ chối</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-zinc-400 text-[11px]">{{ $rev->created_at->format('d/m/Y') }}</td>
                        <td class="p-3.5 text-right space-x-1.5 whitespace-nowrap">
                            @if($rev->status !== 'approved')
                                <form action="{{ route('admin.reviews.status', $rev) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved" />
                                    <button type="submit" class="px-2 py-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-[10px] uppercase">Duyệt</button>
                                </form>
                            @endif

                            @if($rev->status !== 'rejected')
                                <form action="{{ route('admin.reviews.status', $rev) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected" />
                                    <button type="submit" class="px-2 py-1 bg-zinc-100 text-zinc-600 hover:bg-zinc-200 font-bold text-[10px] uppercase">Từ chối</button>
                                </form>
                            @endif

                            <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa vĩnh viễn đánh giá này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 text-rose-600 hover:text-rose-800 font-bold text-[10px] uppercase">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-zinc-400">Không có đánh giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $reviews->links() }}
    </div>
</div>
@endsection
