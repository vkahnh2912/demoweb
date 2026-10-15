@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Quản Lý Mã Giảm Giá (Coupons)</h1>
            <p class="text-zinc-500 mt-1">Tạo và quản lý các mã khuyến mãi, voucher giảm giá cho khách hàng</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="px-4 py-2 bg-zinc-950 text-white font-bold uppercase tracking-wider hover:bg-black">
            + Thêm Mã Mới
        </a>
    </div>

    <div class="bg-white border border-zinc-200 shadow-sm overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 font-bold uppercase tracking-wider text-[11px]">
                <tr>
                    <th class="p-3.5">Mã Coupon</th>
                    <th class="p-3.5">Loại giảm giá</th>
                    <th class="p-3.5">Giá trị</th>
                    <th class="p-3.5">Đơn tối thiểu</th>
                    <th class="p-3.5">Giảm tối đa</th>
                    <th class="p-3.5">Lượt dùng</th>
                    <th class="p-3.5">Thời hạn</th>
                    <th class="p-3.5">Trạng thái</th>
                    <th class="p-3.5 text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                @forelse($coupons as $c)
                    <tr class="hover:bg-zinc-50">
                        <td class="p-3.5 font-bold font-mono text-zinc-900 text-sm">
                            <span class="px-2 py-1 bg-zinc-100 border border-zinc-300">{{ $c->code }}</span>
                        </td>
                        <td class="p-3.5 text-zinc-600">
                            {{ $c->type === 'percentage' ? 'Phần trăm (%)' : 'Số tiền cố định (VNĐ)' }}
                        </td>
                        <td class="p-3.5 font-bold text-zinc-900">
                            {{ $c->type === 'percentage' ? $c->value . '%' : number_format($c->value) . '₫' }}
                        </td>
                        <td class="p-3.5 text-zinc-600">
                            {{ $c->min_order_amount ? number_format($c->min_order_amount) . '₫' : 'Không yêu cầu' }}
                        </td>
                        <td class="p-3.5 text-zinc-600">
                            {{ $c->max_discount_amount ? number_format($c->max_discount_amount) . '₫' : 'Không giới hạn' }}
                        </td>
                        <td class="p-3.5 text-zinc-700">
                            <span class="font-bold">{{ $c->usages_count }}</span>
                            @if($c->usage_limit)
                                <span class="text-zinc-400">/ {{ $c->usage_limit }}</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-[11px] text-zinc-500">
                            @if($c->start_date && $c->end_date)
                                {{ $c->start_date->format('d/m/y') }} - {{ $c->end_date->format('d/m/y') }}
                            @elseif($c->end_date)
                                Hết hạn: {{ $c->end_date->format('d/m/y') }}
                            @else
                                Vô thời hạn
                            @endif
                        </td>
                        <td class="p-3.5">
                            @if($c->status === 'active')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200">Hoạt động</span>
                            @else
                                <span class="px-2 py-0.5 bg-zinc-100 text-zinc-500 font-bold text-[10px]">Tạm dừng</span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right space-x-2">
                            <a href="{{ route('admin.coupons.edit', $c) }}" class="px-2.5 py-1 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-semibold uppercase text-[11px]">Sửa</a>
                            <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa mã giảm giá này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-rose-600 hover:text-rose-800 font-semibold uppercase text-[11px]">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-zinc-400">Chưa có mã giảm giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $coupons->links() }}
    </div>
</div>
@endsection
