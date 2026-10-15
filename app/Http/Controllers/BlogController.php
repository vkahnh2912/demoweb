<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Blog::with(['category', 'author'])->where('status', 'published');

        if ($request->filled('category')) {
            $catSlug = $request->query('category');
            $category = BlogCategory::where('slug', $catSlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $blogs = $query->latest()->paginate(9)->withQueryString();
        $categories = BlogCategory::withCount('posts')->get();
        $recentBlogs = Blog::where('status', 'published')->latest()->take(5)->get();

        return view('blog.index', compact('blogs', 'categories', 'recentBlogs'));
    }

    public function show(string $slug): View
    {
        $blog = Blog::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $blog->increment('views');

        $relatedBlogs = Blog::where('status', 'published')
            ->where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->take(3)
            ->get();

        $categories = BlogCategory::withCount('posts')->get();

        return view('blog.show', compact('blog', 'relatedBlogs', 'categories'));
    }
}
