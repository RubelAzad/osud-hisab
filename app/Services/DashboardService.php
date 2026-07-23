<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function totalSales(): float
    {
        return (float) Sale::sum('total');
    }

    public function totalSellReturn(): float
    {
        return (float) SaleReturn::sum('refund_amount');
    }

    public function netSales(): float
    {
        return $this->totalSales() - $this->totalSellReturn();
    }

    public function invoiceDue(): float
    {
        return (float) Sale::sum('due');
    }

    public function totalPurchase(): float
    {
        return (float) Purchase::sum('total');
    }

    public function purchaseDue(): float
    {
        return (float) Purchase::sum('due');
    }

    public function totalPurchaseReturn(): float
    {
        return (float) PurchaseReturn::sum('amount');
    }

    public function totalExpense(): float
    {
        return (float) Expense::sum('amount');
    }

    /**
     * @return array{labels: array<int, string>, data: array<int, float>}
     */
    public function salesLast30Days(): array
    {
        $rows = Sale::selectRaw('DATE(sale_date) as day, SUM(total) as total')
            ->where('sale_date', '>=', Carbon::today()->subDays(29))
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M Y');
            $data[] = (float) ($rows[$date->format('Y-m-d')] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * @return array{labels: array<int, string>, data: array<int, float>}
     */
    public function salesCurrentFinancialYear(): array
    {
        $year = now()->year;
        $rows = Sale::selectRaw('MONTH(sale_date) as month, SUM(total) as total')
            ->whereYear('sale_date', $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $labels = [];
        $data = [];

        foreach (range(1, 12) as $month) {
            $labels[] = Carbon::create($year, $month, 1)->format('M-Y');
            $data[] = (float) ($rows[$month] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    public function openSaleOrders(int $limit = 10)
    {
        return Quotation::with('customer')
            ->where('type', Quotation::TYPE_QUOTATION)
            ->where('status', Quotation::STATUS_OPEN)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function pendingShipments(int $limit = 10)
    {
        return Sale::with('customer')
            ->whereIn('shipping_status', ['pending', 'shipped'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function customersWithDue(int $limit = 10)
    {
        return Customer::where('balance', '>', 0)->orderByDesc('balance')->limit($limit)->get();
    }

    public function suppliersWithDue(int $limit = 10)
    {
        return Supplier::where('balance', '>', 0)->orderByDesc('balance')->limit($limit)->get();
    }

    public function lowStockMedicines(int $limit = 10)
    {
        return Medicine::query()
            ->withSum('batches as total_stock', 'remaining_qty')
            ->havingRaw('COALESCE(total_stock, 0) <= minimum_stock')
            ->orderBy('total_stock')
            ->limit($limit)
            ->get();
    }

    public function outOfStockMedicines(int $limit = 10)
    {
        return Medicine::query()
            ->withSum('batches as total_stock', 'remaining_qty')
            ->having(fn ($q) => $q->havingRaw('COALESCE(total_stock, 0) <= 0'))
            ->limit($limit)
            ->get();
    }

    public function expiringMedicineBatches(int $days = 30, int $limit = 10)
    {
        return MedicineBatch::with('medicine')
            ->where('remaining_qty', '>', 0)
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [today(), Carbon::today()->addDays($days)])
            ->orderBy('expiry_date')
            ->limit($limit)
            ->get();
    }

    public function topSellingMedicines(int $limit = 10)
    {
        return SaleItem::query()
            ->select('medicine_id', DB::raw('SUM(qty) as total_qty'))
            ->whereHas('sale', fn ($q) => $q->whereMonth('sale_date', now()->month)->whereYear('sale_date', now()->year))
            ->groupBy('medicine_id')
            ->orderByDesc('total_qty')
            ->with('medicine')
            ->limit($limit)
            ->get();
    }
}
