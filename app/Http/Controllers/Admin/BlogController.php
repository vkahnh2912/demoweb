<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::with(['category', 'author'])->latest()->paginate(15);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create(): View
    {
        $categories = BlogCategory::all();
        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|max:3072',
            'status' => 'required|in:published,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $slug = Str::slug($validated['title']);
        if (Blog::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        $image = $validated['image'] ?? null;
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file')->store('blogs', 'public');
        }

        Blog::create([
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'image' => $image,
            'status' => $validated['status'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? null,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Đã tạo bài viết mới thành công!');
    }

    public function edit(Blog $blog): View
    {
        $categories = BlogCategory::all();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|max:3072',
            'status' => 'required|in:published,draft',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $image = $validated['image'] ?? $blog->image;
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file')->store('blogs', 'public');
        }

        $blog->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'image' => $image,
            'status' => $validated['status'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? null,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Đã cập nhật bài viết thành công!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Đã xóa bài viết thành công!');
    }
}
