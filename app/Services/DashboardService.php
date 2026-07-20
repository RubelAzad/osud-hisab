<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function todaySales(): float
    {
        return (float) Sale::whereDate('sale_date', today())->sum('total');
    }

    public function todayPurchases(): float
    {
        return (float) Purchase::whereDate('purchase_date', today())->sum('total');
    }

    public function todayProfit(): float
    {
        return (float) SaleItem::whereHas('sale', fn ($q) => $q->whereDate('sale_date', today()))
            ->join('medicine_batches', 'sale_items.medicine_batch_id', '=', 'medicine_batches.id')
            ->sum(DB::raw('(sale_items.price - medicine_batches.purchase_price) * sale_items.qty'));
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
