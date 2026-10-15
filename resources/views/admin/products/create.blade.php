@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
        <div>
            <h1 class="text-xl font-bold uppercase tracking-tight text-zinc-900">Thêm Sản Phẩm Mới</h1>
            <p class="text-xs text-zinc-500">Tạo sản phẩm thời trang và đăng tải lên hệ thống</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-zinc-600 hover:text-black">
            ← Quay lại danh sách
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 text-xs">
        @csrf

        <!-- 1. Basic Info -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Thông Tin Cơ Bản</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-zinc-700 mb-1">Tên sản phẩm *</label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Ví dụ: Áo Sơ Mi Oxford Dài Tay Relaxed" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                    @error('name') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Mã SKU *</label>
                    <input type="text" name="sku" required value="{{ old('sku') }}" placeholder="Ví dụ: NVW-0051" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 uppercase font-mono" />
                    @error('sku') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Danh mục *</label>
                    <select name="category_id" required class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Thương hiệu / Studio</label>
                    <select name="brand_id" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                        <option value="">Không phân loại thương hiệu</option>
                        @foreach($brands as $b)
                            <option value="{{ $b->id }}" {{ old('brand_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Trạng thái bán *</label>
                    <select name="status" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 bg-white">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Đang mở bán (Active)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Lưu bản nháp (Draft)</option>
                        <option value="out_of_stock" {{ old('status') === 'out_of_stock' ? 'selected' : '' }}>Tạm hết hàng (Out of stock)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. Pricing & Inventory -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Giá & Kho Hàng</h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Giá bán gốc (VNĐ) *</label>
                    <input type="number" name="price" required value="{{ old('price') }}" placeholder="450000" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                    @error('price') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Giá khuyến mãi (VNĐ)</label>
                    <input type="number" name="sale_price" value="{{ old('sale_price') }}" placeholder="390000 (Để trống nếu không sale)" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                    @error('sale_price') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Số lượng trong kho *</label>
                    <input type="number" name="stock" required value="{{ old('stock', 50) }}" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                    @error('stock') <span class="text-rose-600 text-[11px]">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Flags -->
            <div class="pt-2 flex flex-wrap gap-6 border-t border-zinc-100">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="w-4 h-4 text-zinc-950 focus:ring-0" />
                    <span class="font-semibold text-zinc-800">Sản phẩm nổi bật (Featured)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="bestseller" value="1" {{ old('bestseller') ? 'checked' : '' }} class="w-4 h-4 text-zinc-950 focus:ring-0" />
                    <span class="font-semibold text-zinc-800">Bán chạy nhất (Best Seller)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="new_arrival" value="1" {{ old('new_arrival', 1) ? 'checked' : '' }} class="w-4 h-4 text-zinc-950 focus:ring-0" />
                    <span class="font-semibold text-zinc-800">Hàng mới về (New Arrival)</span>
                </label>
            </div>
        </div>

        <!-- 3. Images -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Hình Ảnh Sản Phẩm</h2>

            <div class="space-y-3">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Tải file ảnh từ máy tính (Có thể chọn nhiều ảnh):</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="w-full text-xs p-2 border border-zinc-300 bg-zinc-50" />
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Hoặc dán URL ảnh trực tuyến (Phân cách bằng dấu phẩy hoặc xuống dòng):</label>
                    <textarea name="image_urls" rows="3" placeholder="https://images.unsplash.com/photo-1598033129183-c4f50c736f10..." class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0 font-mono"></textarea>
                </div>
            </div>
        </div>

        <!-- 4. Descriptions -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Mô Tả & Thông Tin Chi Tiết</h2>

            <div class="space-y-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Mô tả ngắn</label>
                    <textarea name="short_description" rows="2" placeholder="Tóm tắt ngắn gọn chất liệu và điểm nổi bật..." class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('short_description') }}</textarea>
                </div>

                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Mô tả đầy đủ chi tiết</label>
                    <textarea name="description" rows="6" placeholder="Giới thiệu đầy đủ về sản phẩm, hướng dẫn mix match, lưu ý bảo quản..." class="w-full text-xs p-3 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 5. SEO -->
        <div class="bg-white p-6 border border-zinc-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-900 border-b border-zinc-100 pb-2">Cấu Hình SEO</h2>

            <div class="space-y-4">
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Thẻ tiêu đề SEO (Meta Title)</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Để trống sẽ tự động lấy tên sản phẩm" class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0" />
                </div>
                <div>
                    <label class="block font-semibold text-zinc-700 mb-1">Mô tả SEO (Meta Description)</label>
                    <textarea name="meta_description" rows="2" placeholder="Đoạn văn ngắn 150-160 ký tự cho Google..." class="w-full text-xs p-2.5 border-zinc-300 focus:border-zinc-950 focus:ring-0">{{ old('meta_description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Form Submit -->
        <div class="flex items-center gap-3">
            <button type="submit" class="px-8 py-3 bg-zinc-950 hover:bg-zinc-800 text-white text-xs font-bold uppercase tracking-widest transition-colors shadow-lg">
                Lưu & Đăng Sản Phẩm
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 bg-white border border-zinc-300 text-zinc-700 text-xs font-semibold uppercase tracking-wider hover:bg-zinc-50 transition-colors">
                Hủy Bỏ
            </a>
        </div>
    </form>

</div>
@endsection
