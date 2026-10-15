<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['product.images', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $review->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đánh giá thành công!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Đã xóa đánh giá thành công!');
    }
}
