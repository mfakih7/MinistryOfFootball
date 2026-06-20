<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_orders' => Order::query()->count(),
            'pending_orders' => Order::query()->where('status', OrderStatus::Pending)->count(),
            'confirmed_orders' => Order::query()->where('status', OrderStatus::Confirmed)->count(),
            'delivered_orders' => Order::query()->where('status', OrderStatus::Delivered)->count(),
            'total_customers' => Customer::query()->count(),
            'revenue' => Order::query()
                ->whereIn('status', [
                    OrderStatus::Confirmed,
                    OrderStatus::Preparing,
                    OrderStatus::Delivered,
                ])
                ->sum('total'),
            'total_products' => Product::query()->count(),
            'active_products' => Product::query()->active()->count(),
            'low_stock_variants' => ProductVariant::query()->where('stock_quantity', '<=', 5)->count(),
        ];

        $recentProducts = Product::query()->latest()->limit(5)->get();
        $recentOrders = Order::query()->with('customer')->latest()->limit(8)->get();

        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'recentOrders' => $recentOrders,
        ]);
    }
}
