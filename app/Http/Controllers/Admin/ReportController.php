<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportService $reports
    ) {}

    public function index(Request $request): View
    {
        $summary = $this->reports->summary($request);
        $salesByDay = $this->reports->salesByDay($request);
        $ordersByStatus = $this->reports->ordersByStatus($request);
        $topProducts = $this->reports->topProducts($request);
        $lowStock = $this->reports->lowStockVariants();
        $newCustomers = $this->reports->newCustomersThisMonth();

        return view('admin.reports.index', compact(
            'summary',
            'salesByDay',
            'ordersByStatus',
            'topProducts',
            'lowStock',
            'newCustomers'
        ));
    }
}
