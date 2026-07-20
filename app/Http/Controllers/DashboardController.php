<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboardService) {}

    public function index(): View
    {
        return view('dashboard.index', [
            'todaySales' => $this->dashboardService->todaySales(),
            'todayPurchases' => $this->dashboardService->todayPurchases(),
            'todayProfit' => $this->dashboardService->todayProfit(),
            'lowStockMedicines' => $this->dashboardService->lowStockMedicines(),
            'outOfStockMedicines' => $this->dashboardService->outOfStockMedicines(),
            'expiringBatches' => $this->dashboardService->expiringMedicineBatches(),
            'topSellingMedicines' => $this->dashboardService->topSellingMedicines(),
        ]);
    }
}
