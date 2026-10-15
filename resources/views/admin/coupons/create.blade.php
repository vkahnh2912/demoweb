@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 text-xs">
    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Thêm Mã Giảm Giá Mới</h1>
        <a href="{{ route('admin.coupons.index') }}" class="font-semibold text-zinc-600 hover:text-black">← Quay lại</a>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Mã Coupon (Code) *</label>
                <input type="text" name="code" required value="{{ old('code') }}" placeholder="VD: NOVA10, FREESHIP" class="w-full text-xs p-2.5 uppercase font-mono border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                @error('code') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Loại giảm giá *</label>
                <select name="type" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Giá trị giảm *</label>
                <input type="number" step="0.01" name="value" required value="{{ old('value') }}" placeholder="VD: 10 (cho 10%) hoặc 50000" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                @error('value') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Đơn tối thiểu (VNĐ)</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}" placeholder="Để trống nếu không giới hạn" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Giảm tối đa (VNĐ)</label>
                <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount') }}" placeholder="Áp dụng cho %" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Ngày bắt đầu</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Ngày kết thúc</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
            <div>
                <label class="block font-semibold text-zinc-700 mb-1">Giới hạn số lần dùng</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="VD: 100" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            </div>
        </div>

        <div>
            <label class="block font-semibold text-zinc-700 mb-1">Trạng thái *</label>
            <select name="status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                <option value="active">Đang áp dụng (Active)</option>
                <option value="inactive">Tạm ngưng (Inactive)</option>
            </select>
        </div>

        <div class="pt-4 border-t border-zinc-100 flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-zinc-950 text-white font-bold uppercase tracking-wider">Tạo Mã Giảm Giá</button>
            <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2.5 bg-white border border-zinc-300 text-zinc-700 font-semibold uppercase">Hủy</a>
        </div>
    </form>
</div>
@endsection
