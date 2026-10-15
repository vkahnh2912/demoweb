<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalOrders = Order::count();
        $revenue = Order::where('status', 'completed')->sum('total');
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();

        $recentOrders = Order::with('items')->latest()->take(6)->get();

        // Top 5 selling products
        $topProducts = Product::with('images')
            ->orderBy('stock', 'asc') // or by sales
            ->take(5)
            ->get();

        // Monthly revenue for the last 6 months
        $dateExpr = DB::getDriverName() === 'sqlite' 
            ? 'strftime("%Y-%m", created_at)' 
            : 'DATE_FORMAT(created_at, "%Y-%m")';

        $monthlyRevenue = Order::select(
            DB::raw("{$dateExpr} as month"),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(*) as count')
        )
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'revenue',
            'totalCustomers',
            'totalProducts',
            'pendingOrders',
            'completedOrders',
            'recentOrders',
            'topProducts',
            'monthlyRevenue'
        ));
    }
}
