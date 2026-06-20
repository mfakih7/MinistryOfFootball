<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function summary(Request $request): array
    {
        $query = $this->baseQuery($request);

        $revenueStatuses = [
            OrderStatus::Confirmed,
            OrderStatus::Preparing,
            OrderStatus::Delivered,
        ];

        $totalOrders = (clone $query)->count();
        $totalRevenue = (clone $query)->whereIn('status', $revenueStatuses)->sum('total');
        $couponDiscounts = (clone $query)->sum('discount_total');

        return [
            'total_revenue' => (float) $totalRevenue,
            'total_orders' => $totalOrders,
            'pending_orders' => (clone $query)->where('status', OrderStatus::Pending)->count(),
            'delivered_orders' => (clone $query)->where('status', OrderStatus::Delivered)->count(),
            'cancelled_orders' => (clone $query)->where('status', OrderStatus::Cancelled)->count(),
            'total_customers' => Customer::query()->count(),
            'average_order_value' => $totalOrders > 0 ? round($totalRevenue / max(1, (clone $query)->whereIn('status', $revenueStatuses)->count()), 2) : 0,
            'coupon_discounts_total' => (float) $couponDiscounts,
        ];
    }

    public function salesByDay(Request $request, int $days = 30): Collection
    {
        $from = $this->dateFrom($request) ?? now()->subDays($days - 1)->startOfDay();
        $to = $this->dateTo($request) ?? now()->endOfDay();

        return Order::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as orders_count, SUM(total) as revenue')
            ->whereBetween('created_at', [$from, $to])
            ->whereIn('status', [OrderStatus::Confirmed, OrderStatus::Preparing, OrderStatus::Delivered])
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    public function ordersByStatus(Request $request): Collection
    {
        return $this->baseQuery($request)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
    }

    public function topProducts(Request $request, int $limit = 10): Collection
    {
        $from = $this->dateFrom($request);
        $to = $this->dateTo($request);

        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->when($from, fn ($q) => $q->where('orders.created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('orders.created_at', '<=', $to))
            ->whereNotIn('orders.status', [OrderStatus::Cancelled->value])
            ->selectRaw('order_items.product_name, SUM(order_items.quantity) as total_qty, SUM(order_items.total_price) as total_revenue')
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();
    }

    public function lowStockVariants(int $threshold = 5): Collection
    {
        return ProductVariant::query()
            ->with(['product', 'size', 'color'])
            ->where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity')
            ->limit(15)
            ->get();
    }

    public function newCustomersThisMonth(): int
    {
        return Customer::query()
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
    }

    protected function baseQuery(Request $request)
    {
        return Order::query()
            ->when($this->dateFrom($request), fn ($q, $date) => $q->where('created_at', '>=', $date))
            ->when($this->dateTo($request), fn ($q, $date) => $q->where('created_at', '<=', $date));
    }

    protected function dateFrom(Request $request): ?Carbon
    {
        return $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : null;
    }

    protected function dateTo(Request $request): ?Carbon
    {
        return $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : null;
    }
}
