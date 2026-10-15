<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroBanners = Banner::where('position', 'hero')->where('status', 'active')->orderBy('sort_order')->get();
        $promoBanner = Banner::where('position', 'promo')->where('status', 'active')->first();

        $featuredCategories = Category::where('status', 'active')
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $newArrivals = Product::with(['images', 'category'])
            ->active()
            ->where('new_arrival', true)
            ->latest()
            ->take(8)
            ->get();

        $bestSellers = Product::with(['images', 'category'])
            ->active()
            ->where('bestseller', true)
            ->latest()
            ->take(8)
            ->get();

        $featuredProducts = Product::with(['images', 'category'])
            ->active()
            ->where('featured', true)
            ->latest()
            ->take(8)
            ->get();

        $brands = Brand::where('status', 'active')->where('is_featured', true)->take(6)->get();

        $latestBlogs = Blog::with('category')
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        return view('home.index', compact(
            'heroBanners',
            'promoBanner',
            'featuredCategories',
            'newArrivals',
            'bestSellers',
            'featuredProducts',
            'brands',
            'latestBlogs'
        ));
    }
}
