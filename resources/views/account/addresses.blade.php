@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ showAddForm: false }">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Tài Khoản', 'url' => route('account.dashboard')],
        ['label' => 'Sổ Địa Chỉ']
    ]" />

    <div class="py-4 border-b border-zinc-200 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight text-zinc-950">
                Sổ Địa Chỉ Của Bạn
            </h1>
            <p class="text-xs text-zinc-500 mt-1">Quản lý các địa chỉ giao hàng để đặt hàng nhanh chóng hơn.</p>
        </div>
        <div>
            <button type="button" @click="showAddForm = !showAddForm" class="px-6 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs uppercase font-semibold tracking-wider transition-colors">
                + Thêm Địa Chỉ Mới
            </button>
        </div>
    </div>

    <!-- ADD ADDRESS FORM (Collapsible) -->
    <div x-show="showAddForm" x-transition class="border border-zinc-200 bg-zinc-50 p-6 mb-8 max-w-2xl" style="display: none;">
        <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-950 mb-4 pb-2 border-b border-zinc-200">
            Thêm Địa Chỉ Giao Hàng Mới
        </h2>
        <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-zinc-700 mb-1">Họ và tên *</label>
                    <input type="text" name="full_name" required value="{{ old('full_name') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Số điện thoại *</label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Tỉnh / Thành phố</label>
                    <input type="text" name="province" value="{{ old('province', 'TP. Hồ Chí Minh') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Quận / Huyện</label>
                    <input type="text" name="district" value="{{ old('district', 'Quận 1') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Phường / Xã</label>
                    <input type="text" name="ward" value="{{ old('ward', 'Phường Bến Nghé') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white" />
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-zinc-700 mb-1">Địa chỉ chi tiết (Số nhà, đường) *</label>
                    <input type="text" name="address" required value="{{ old('address') }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white" />
                </div>
                <div class="sm:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer text-zinc-700">
                        <input type="checkbox" name="is_default" value="1" class="w-4 h-4 text-zinc-950 border-zinc-300 focus:ring-0" />
                        <span>Đặt làm địa chỉ mặc định</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-zinc-950 text-white font-semibold uppercase tracking-wider">
                    Lưu Địa Chỉ
                </button>
                <button type="button" @click="showAddForm = false" class="px-6 py-2.5 bg-white border border-zinc-300 text-zinc-700 font-semibold uppercase tracking-wider">
                    Hủy Bỏ
                </button>
            </div>
        </form>
    </div>

    <!-- ADDRESSES LIST -->
    @if($addresses->isEmpty())
        <div class="py-16 text-center text-xs text-zinc-500">
            Bạn chưa lưu địa chỉ nào trong sổ địa chỉ.
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($addresses as $addr)
                <div class="border {{ $addr->is_default ? 'border-zinc-950' : 'border-zinc-200' }} bg-white p-6 relative flex flex-col justify-between space-y-4 text-xs">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <strong class="text-sm font-bold text-zinc-950">{{ $addr->full_name }}</strong>
                            @if($addr->is_default)
                                <span class="px-2 py-0.5 bg-zinc-950 text-white text-[10px] uppercase font-bold tracking-wider">Mặc định</span>
                            @endif
                        </div>
                        <p class="text-zinc-600">SĐT: {{ $addr->phone }}</p>
                        <p class="text-zinc-700 font-medium pt-1">{{ $addr->address }}</p>
                        @if($addr->province)
                            <p class="text-zinc-500">{{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }}</p>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-zinc-100 flex items-center justify-end gap-3">
                        <form action="{{ route('account.addresses.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-600 hover:underline font-semibold text-[11px]">
                                Xóa
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
