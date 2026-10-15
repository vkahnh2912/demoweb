@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Quản Lý Sản Phẩm ({{ $products->total() }})</h1>
            <p class="text-xs text-zinc-500 mt-0.5">Danh sách toàn bộ sản phẩm thời trang trong kho hàng</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-zinc-950 hover:bg-zinc-800 text-white text-xs uppercase font-bold tracking-wider transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Thêm Sản Phẩm Mới</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 border border-zinc-200">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm theo tên hoặc mã SKU..." class="text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
            <select name="category_id" class="text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="status" class="text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                <option value="">Tất cả trạng thái</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang bán</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Bản nháp</option>
                <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-zinc-900 text-white font-semibold uppercase tracking-wider text-xs">
                    Lọc
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-zinc-100 text-zinc-700 font-semibold uppercase tracking-wider text-xs hover:bg-zinc-200 flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-zinc-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-zinc-500 uppercase text-[11px]">
                    <tr>
                        <th class="py-3 px-4">Ảnh</th>
                        <th class="py-3 px-4">Tên sản phẩm</th>
                        <th class="py-3 px-4">SKU</th>
                        <th class="py-3 px-4">Danh mục</th>
                        <th class="py-3 px-4">Giá bán</th>
                        <th class="py-3 px-4">Kho</th>
                        <th class="py-3 px-4">Trạng thái</th>
                        <th class="py-3 px-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($products as $p)
                        <tr class="hover:bg-zinc-50/50">
                            <td class="py-3 px-4">
                                <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}" class="w-12 h-16 object-cover bg-zinc-100" />
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                <a href="{{ route('product.show', $p->slug) }}" target="_blank" class="font-bold text-zinc-950 hover:underline line-clamp-1">
                                    {{ $p->name }}
                                </a>
                                <div class="flex gap-1.5 mt-1">
                                    @if($p->featured) <span class="px-1.5 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold">HOT</span> @endif
                                    @if($p->new_arrival) <span class="px-1.5 py-0.5 bg-zinc-900 text-white text-[10px] font-bold">MỚI</span> @endif
                                    @if($p->bestseller) <span class="px-1.5 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-bold">BÁN CHẠY</span> @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-zinc-700">{{ $p->sku }}</td>
                            <td class="py-3 px-4 text-zinc-600">{{ $p->category?->name ?? '—' }}</td>
                            <td class="py-3 px-4 font-semibold text-zinc-950">
                                @if($p->sale_price)
                                    <span class="text-rose-600">{{ number_format($p->sale_price) }}₫</span>
                                    <span class="text-[10px] text-zinc-400 line-through block">{{ number_format($p->price) }}₫</span>
                                @else
                                    <span>{{ number_format($p->price) }}₫</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="{{ $p->stock <= 10 ? 'text-rose-600 font-bold' : 'text-zinc-700' }}">{{ $p->stock }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                    {{ $p->status === 'active' ? 'bg-emerald-50 text-emerald-700' : '' }}
                                    {{ $p->status === 'draft' ? 'bg-zinc-100 text-zinc-700' : '' }}
                                    {{ $p->status === 'out_of_stock' ? 'bg-rose-50 text-rose-700' : '' }}
                                ">
                                    {{ $p->status === 'active' ? 'Đang bán' : ($p->status === 'draft' ? 'Bản nháp' : 'Hết hàng') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="text-zinc-900 hover:underline font-bold">Sửa</a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:underline font-bold">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-zinc-400">Không tìm thấy sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-zinc-200">
            <x-pagination :paginator="$products" />
        </div>
    </div>

</div>
@endsection
