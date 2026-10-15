<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::withCount('products')->paginate(15);
        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = Str::slug($validated['name']);
        if (Brand::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        Brand::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'logo' => $validated['logo'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Đã thêm thương hiệu mới thành công!');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $brand->update([
            'name' => $validated['name'],
            'logo' => $validated['logo'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Đã cập nhật thương hiệu thành công!');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Đã xóa thương hiệu thành công!');
    }
}
