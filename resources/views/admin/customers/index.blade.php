@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Quản Lý Khách Hàng</h1>
            <p class="text-zinc-500 mt-1">Danh sách tài khoản khách hàng đăng ký trên NOVAWEAR</p>
        </div>

        <form method="GET" action="{{ route('admin.customers.index') }}" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tên / Email / SĐT..." class="text-xs p-2 border-zinc-300 focus:border-zinc-950 focus:ring-0 w-48 sm:w-64" />
            <button type="submit" class="px-4 py-2 bg-zinc-900 text-white font-semibold uppercase tracking-wider">Lọc</button>
            @if(request('q'))
                <a href="{{ route('admin.customers.index') }}" class="p-2 text-zinc-400 hover:text-black">✕ Xóa lọc</a>
            @endif
        </form>
    </div>

    <div class="bg-white border border-zinc-200 shadow-sm overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="p-3.5">Khách hàng</th>
                    <th class="p-3.5">Số điện thoại</th>
                    <th class="p-3.5">Tổng số đơn</th>
                    <th class="p-3.5">Trạng thái</th>
                    <th class="p-3.5">Ngày tham gia</th>
                    <th class="p-3.5 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse($customers as $c)
                    <tr class="hover:bg-zinc-50">
                        <td class="p-3.5 font-semibold text-zinc-900">
                            <div>{{ $c->name }}</div>
                            <div class="text-[11px] text-zinc-400 font-normal">{{ $c->email }}</div>
                        </td>
                        <td class="p-3.5 text-zinc-600">{{ $c->phone ?? '—' }}</td>
                        <td class="p-3.5 font-bold text-zinc-900">{{ $c->orders_count }} đơn</td>
                        <td class="p-3.5">
                            @if($c->status === 'active')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">Hoạt động</span>
                            @else
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-700 font-bold text-[10px] border border-rose-200">Đã khóa</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-zinc-400 text-[11px]">{{ $c->created_at->format('d/m/Y') }}</td>
                        <td class="p-3.5 text-right space-x-2">
                            <a href="{{ route('admin.customers.show', $c) }}" class="px-3 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-semibold uppercase text-[11px]">
                                Xem hồ sơ
                            </a>
                            <form action="{{ route('admin.customers.toggle-status', $c) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn đổi trạng thái tài khoản này?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-2.5 py-1 text-[11px] font-semibold {{ $c->status === 'active' ? 'text-rose-600 hover:text-rose-800' : 'text-emerald-600 hover:text-emerald-800' }}">
                                    {{ $c->status === 'active' ? 'Khóa' : 'Kích hoạt' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-zinc-400">Không tìm thấy khách hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $customers->links() }}
    </div>
</div>
@endsection
