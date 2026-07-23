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
            'totalSales' => $this->dashboardService->totalSales(),
            'netSales' => $this->dashboardService->netSales(),
            'invoiceDue' => $this->dashboardService->invoiceDue(),
            'totalSellReturn' => $this->dashboardService->totalSellReturn(),
            'totalPurchase' => $this->dashboardService->totalPurchase(),
            'purchaseDue' => $this->dashboardService->purchaseDue(),
            'totalPurchaseReturn' => $this->dashboardService->totalPurchaseReturn(),
            'totalExpense' => $this->dashboardService->totalExpense(),
            'salesLast30Days' => $this->dashboardService->salesLast30Days(),
            'salesCurrentFinancialYear' => $this->dashboardService->salesCurrentFinancialYear(),
            'openSaleOrders' => $this->dashboardService->openSaleOrders(),
            'pendingShipments' => $this->dashboardService->pendingShipments(),
            'customersWithDue' => $this->dashboardService->customersWithDue(),
            'suppliersWithDue' => $this->dashboardService->suppliersWithDue(),
            'lowStockMedicines' => $this->dashboardService->lowStockMedicines(),
            'outOfStockMedicines' => $this->dashboardService->outOfStockMedicines(),
            'expiringBatches' => $this->dashboardService->expiringMedicineBatches(),
            'topSellingMedicines' => $this->dashboardService->topSellingMedicines(),
        ]);
    }
}
