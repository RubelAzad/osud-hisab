<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    private const REPORTS = [
        'daily-sales' => ['title' => 'Daily Sales', 'method' => 'dailySales', 'filter' => 'date'],
        'monthly-sales' => ['title' => 'Monthly Sales', 'method' => 'monthlySales', 'filter' => 'month'],
        'profit-loss' => ['title' => 'Profit & Loss', 'method' => 'profitLoss', 'filter' => 'range'],
        'purchases' => ['title' => 'Purchase Report', 'method' => 'purchases', 'filter' => 'range'],
        'supplier-due' => ['title' => 'Supplier Due', 'method' => 'supplierDue', 'filter' => 'none'],
        'customer-due' => ['title' => 'Customer Due', 'method' => 'customerDue', 'filter' => 'none'],
        'stock' => ['title' => 'Stock Report', 'method' => 'stock', 'filter' => 'none'],
        'low-stock' => ['title' => 'Low Stock', 'method' => 'lowStock', 'filter' => 'none'],
        'expired' => ['title' => 'Expired Medicines', 'method' => 'expired', 'filter' => 'none'],
        'near-expiry' => ['title' => 'Near Expiry', 'method' => 'nearExpiry', 'filter' => 'days'],
        'vat' => ['title' => 'VAT Report', 'method' => 'vat', 'filter' => 'range'],
        'best-selling' => ['title' => 'Best Selling Medicines', 'method' => 'bestSelling', 'filter' => 'range'],
        'slow-moving' => ['title' => 'Slow Moving Medicines', 'method' => 'slowMoving', 'filter' => 'days'],
        'cash-book' => ['title' => 'Cash Book', 'method' => 'cashBook', 'filter' => 'range'],
        'expenses' => ['title' => 'Expense Report', 'method' => 'expenses', 'filter' => 'range'],
        'purchase-returns' => ['title' => 'Purchase Return Report', 'method' => 'purchaseReturns', 'filter' => 'range'],
        'sales-returns' => ['title' => 'Sales Return Report', 'method' => 'salesReturns', 'filter' => 'range'],
    ];

    public function __construct(private readonly ReportService $reportService) {}

    public function index(): View
    {
        return view('reports.index', ['reports' => self::REPORTS]);
    }

    public function show(string $report, Request $request): View
    {
        abort_unless(isset(self::REPORTS[$report]), 404);
        $meta = self::REPORTS[$report];

        $args = match ($meta['filter']) {
            'date' => [$request->input('date', now()->format('Y-m-d'))],
            'month' => [(int) $request->input('year', now()->year), (int) $request->input('month', now()->month)],
            'range' => [$request->input('from', now()->startOfMonth()->format('Y-m-d')), $request->input('to', now()->format('Y-m-d'))],
            'days' => [(int) $request->input('days', 30)],
            default => [],
        };

        $data = $this->reportService->{$meta['method']}(...$args);

        return view('reports.show', [
            'title' => $meta['title'],
            'slug' => $report,
            'filterType' => $meta['filter'],
            'filters' => $request->only(['date', 'year', 'month', 'from', 'to', 'days']),
            'data' => $data,
        ]);
    }
}
