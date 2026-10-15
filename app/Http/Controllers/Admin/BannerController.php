<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        $banners = Banner::orderBy('position')->orderBy('sort_order')->paginate(15);
        return view('admin.banners.index', compact('banners'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|max:3072',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'position' => 'required|in:hero,promo,sidebar',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('banners', 'public');
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Đã thêm banner mới thành công!');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|max:3072',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'position' => 'required|in:hero,promo,sidebar',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image'] = $request->file('image_file')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Đã cập nhật banner thành công!');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Đã xóa banner thành công!');
    }
}
