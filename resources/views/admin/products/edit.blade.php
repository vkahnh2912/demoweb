@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Chỉnh Sửa Sản Phẩm</h1>
            <p class="text-xs text-zinc-500">Cập nhật thông tin cho sản phẩm: <strong>{{ $product->name }}</strong></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-xs font-semibold text-zinc-900 hover:underline">
                Xem trên web ↗
            </a>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-zinc-600 hover:text-black">
                ← Quay lại
            </a>
        </div>
    </div>

    <!-- GALLERY MANAGEMENT -->
    <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
        <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">
            Thư Viện Ảnh Hiện Tại ({{ $product->images->count() }})
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
            @forelse($product->images as $img)
                <div class="relative group border {{ $img->is_primary ? 'border-zinc-950 ring-2 ring-zinc-950' : 'border-zinc-200' }} aspect-[3/4] bg-zinc-100 overflow-hidden flex flex-col justify-between">
                    <img src="{{ $img->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />

                    @if($img->is_primary)
                        <span class="absolute top-1 left-1 px-1.5 py-0.5 bg-zinc-950 text-white text-[9px] font-bold uppercase tracking-wider">
                            Chính
                        </span>
                    @endif

                    <div class="absolute inset-x-0 bottom-0 p-1.5 bg-black/70 flex justify-between items-center opacity-0 group-hover:opacity-100 transition-opacity">
                        @if(!$img->is_primary)
                            <form action="{{ route('admin.products.images.primary', [$product->id, $img->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] text-white hover:underline">Đặt chính</button>
                            </form>
                        @else
                            <span></span>
                        @endif

                        <form action="{{ route('admin.products.images.destroy', [$product->id, $img->id]) }}" method="POST" onsubmit="return confirm('Xóa ảnh này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[10px] text-rose-400 hover:text-rose-300">Xóa</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-xs text-zinc-400 col-span-full py-4 text-center">Chưa có ảnh nào cho sản phẩm này.</p>
            @endforelse
        </div>
    </div>

    <!-- MAIN PRODUCT EDIT FORM -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 text-xs">
        @csrf
        @method('PUT')

        <!-- 1. Basic Info -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Thông Tin Cơ Bản</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-zinc-700 mb-1">Tên sản phẩm *</label>
                    <input type="text" name="name" required value="{{ old('name', $product->name) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                    @error('name') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Mã SKU *</label>
                    <input type="text" name="sku" required value="{{ old('sku', $product->sku) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 uppercase font-mono" />
                    @error('sku') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Danh mục *</label>
                    <select name="category_id" required class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Thương hiệu / Studio</label>
                    <select name="brand_id" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                        <option value="">Không phân loại thương hiệu</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}" {{ old('brand_id', $product->brand_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Trạng thái bán *</label>
                    <select name="status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                        <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Đang mở bán (Active)</option>
                        <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Lưu bản nháp (Draft)</option>
                        <option value="out_of_stock" {{ old('status', $product->status) === 'out_of_stock' ? 'selected' : '' }}>Tạm hết hàng (Out of stock)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. Pricing & Stock -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Giá & Kho Hàng</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Giá bán gốc (VNĐ) *</label>
                    <input type="number" name="price" required value="{{ old('price', (int)$product->price) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Giá khuyến mãi (VNĐ)</label>
                    <input type="number" name="sale_price" value="{{ old('sale_price', (int)$product->sale_price) }}" placeholder="Để trống nếu không sale" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Số lượng trong kho *</label>
                    <input type="number" name="stock" required value="{{ old('stock', $product->stock) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
            </div>

            <!-- Flags -->
            <div class="pt-2 flex flex-wrap gap-6 border-t border-zinc-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }} class="w-4 h-4 text-zinc-950 focus:ring-0" />
                    <span class="font-semibold text-zinc-800">Sản phẩm nổi bật</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="bestseller" value="1" {{ old('bestseller', $product->bestseller) ? 'checked' : '' }} class="w-4 h-4 text-zinc-950 focus:ring-0" />
                    <span class="font-semibold text-zinc-800">Bán chạy nhất</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="new_arrival" value="1" {{ old('new_arrival', $product->new_arrival) ? 'checked' : '' }} class="w-4 h-4 text-zinc-950 focus:ring-0" />
                    <span class="font-semibold text-zinc-800">Hàng mới về</span>
                </label>
            </div>
        </div>

        <!-- 3. Add More Images -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Thêm Ảnh Mới</h2>

            <div class="space-y-3">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Tải thêm file ảnh mới:</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs p-2 border border-zinc-300 bg-zinc-50" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Hoặc thêm URL ảnh trực tuyến:</label>
                    <textarea name="image_urls" rows="2" placeholder="https://..." class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 font-mono"></textarea>
                </div>
            </div>
        </div>

        <!-- 4. Descriptions -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Mô Tả</h2>

            <div class="space-y-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Mô tả ngắn</label>
                    <textarea name="short_description" rows="2" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('short_description', $product->short_description) }}</textarea>
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Mô tả đầy đủ chi tiết</label>
                    <textarea name="description" rows="6" class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 5. SEO -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Cấu Hình SEO</h2>

            <div class="space-y-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Thẻ tiêu đề SEO (Meta Title)</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Mô tả SEO (Meta Description)</label>
                    <textarea name="meta_description" rows="2" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('meta_description', $product->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-3">
            <button type="submit" class="px-8 py-3 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-bold uppercase tracking-widest transition-colors shadow-lg">
                Cập Nhật Sản Phẩm
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 bg-white border border-zinc-300 text-zinc-700 text-xs font-semibold uppercase tracking-wider hover:bg-zinc-50 transition-colors">
                Hủy Bỏ
            </a>
        </div>
    </form>

</div>
@endsection
