@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $card = function (string $label, float $value, string $icon, string $color, string $trend = '') {
        return compact('label', 'value', 'icon', 'color', 'trend');
    };
    $cards = [
        $card('Total Sales', $totalSales, 'bi-cart-check-fill', 'primary', ''),
        $card('Net Sales', $netSales, 'bi-cash-stack', 'success', ''),
        $card('Invoice Due', $invoiceDue, 'bi-file-earmark-text', 'warning', ''),
        $card('Sales Return', $totalSellReturn, 'bi-arrow-return-left', 'danger', ''),
        $card('Total Purchase', $totalPurchase, 'bi-bag-check-fill', 'info', ''),
        $card('Purchase Due', $purchaseDue, 'bi-exclamation-triangle', 'warning', ''),
        $card('Purchase Return', $totalPurchaseReturn, 'bi-arrow-90deg-left', 'danger', ''),
        $card('Total Expense', $totalExpense, 'bi-receipt-cutoff', 'secondary', ''),
    ];

    $colorMap = [
        'primary' => ['bg' => 'rgba(59,130,246,.1)', 'text' => '#3b82f6', 'border' => '#3b82f6'],
        'success' => ['bg' => 'rgba(34,197,94,.1)', 'text' => '#22c55e', 'border' => '#22c55e'],
        'warning' => ['bg' => 'rgba(245,158,11,.1)', 'text' => '#f59e0b', 'border' => '#f59e0b'],
        'danger'  => ['bg' => 'rgba(239,68,68,.1)', 'text' => '#ef4444', 'border' => '#ef4444'],
        'info'    => ['bg' => 'rgba(14,165,233,.1)', 'text' => '#0ea5e9', 'border' => '#0ea5e9'],
        'secondary' => ['bg' => 'rgba(100,116,139,.1)', 'text' => '#64748b', 'border' => '#64748b'],
    ];
@endphp

@section('content')
<div class="page-header d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h4>Dashboard</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Welcome back, {{ auth()->user()->name }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        @can('sales.create')
            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-shop me-1"></i> New Sale
            </a>
        @endcan
        @can('purchases.create')
            <a href="{{ route('purchases.create') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> New Purchase
            </a>
        @endcan
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    @foreach ($cards as $c)
        @php $cm = $colorMap[$c['color']] ?? $colorMap['secondary']; @endphp
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card stat-card h-100" style="border-left-color: {{ $cm['border'] }};">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background: {{ $cm['bg'] }}; color: {{ $cm['text'] }};">
                        <i class="bi {{ $c['icon'] }}"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="stat-label">{{ $c['label'] }}</div>
                        <div class="stat-value">{{ number_format($c['value'], 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span>Sales Last 30 Days</span>
            </div>
            <div class="card-body">
                <canvas id="salesLast30DaysChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span>Sales Current Financial Year</span>
            </div>
            <div class="card-body">
                <canvas id="salesCurrentFinancialYearChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Payment Due Tables -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-badge me-2 text-primary"></i>Sale Payment Due</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th class="text-end">Due Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customersWithDue as $customer)
                            <tr>
                                <td><a href="{{ route('customers.show', $customer) }}" class="text-decoration-none fw-medium">{{ $customer->name }}</a></td>
                                <td class="text-muted">{{ $customer->phone }}</td>
                                <td class="text-end fw-semibold text-danger">{{ number_format($customer->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted text-center py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No due payments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-truck me-2 text-info"></i>Purchase Payment Due</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Phone</th>
                            <th class="text-end">Due Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliersWithDue as $supplier)
                            <tr>
                                <td><a href="{{ route('suppliers.show', $supplier) }}" class="text-decoration-none fw-medium">{{ $supplier->name }}</a></td>
                                <td class="text-muted">{{ $supplier->phone }}</td>
                                <td class="text-end fw-semibold text-danger">{{ number_format($supplier->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted text-center py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No due payments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Stock Alerts -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle text-warning me-2"></i>Product Stock Alert</span>
                <span class="badge bg-warning text-dark">{{ $lowStockMedicines->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-end">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockMedicines as $medicine)
                            <tr>
                                <td><a href="{{ route('medicines.show', $medicine) }}" class="text-decoration-none">{{ $medicine->medicine_name }}</a></td>
                                <td class="text-end">
                                    <span class="fw-semibold">{{ $medicine->total_stock ?? 0 }}</span>
                                    <span class="text-muted"> / {{ $medicine->minimum_stock }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-muted text-center py-4"><i class="bi bi-check-circle fs-3 d-block mb-2"></i>All stock levels OK</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-x text-danger me-2"></i>Stock Expiry Alert</span>
                <span class="badge bg-danger">{{ $expiringBatches->count() }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Batch</th>
                            <th class="text-end">Expiry</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expiringBatches as $batch)
                            <tr>
                                <td>{{ $batch->medicine->medicine_name ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark">{{ $batch->batch_no }}</span></td>
                                <td class="text-end text-danger fw-medium">{{ $batch->expiry_date->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted text-center py-4"><i class="bi bi-check-circle fs-3 d-block mb-2"></i>No expiring batches</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Orders & Shipments -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white"><i class="bi bi-clipboard-check me-2 text-info"></i>Sale Orders</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($openSaleOrders as $quotation)
                            <tr>
                                <td>{{ $quotation->quotation_date->format('Y-m-d') }}</td>
                                <td><a href="{{ route('quotations.show', $quotation) }}" class="text-decoration-none">#{{ $quotation->id }}</a></td>
                                <td>{{ $quotation->customer->name ?? 'Walk-in' }}</td>
                                <td><span class="badge bg-info-subtle text-info">{{ ucfirst($quotation->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted text-center py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No open orders</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white"><i class="bi bi-box-seam me-2 text-warning"></i>Pending Shipments</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingShipments as $shipment)
                            <tr>
                                <td>{{ $shipment->sale_date->format('Y-m-d') }}</td>
                                <td><a href="{{ route('sales.show', $shipment) }}" class="text-decoration-none">{{ $shipment->invoice_no }}</a></td>
                                <td>{{ $shipment->customer->name ?? 'Walk-in' }}</td>
                                <td><span class="badge bg-warning text-dark">{{ ucfirst($shipment->shipping_status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted text-center py-4"><i class="bi bi-check-circle fs-3 d-block mb-2"></i>No pending shipments</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row -->
<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-x-octagon text-danger me-2"></i>Out of Stock</span>
                <span class="badge bg-danger">{{ $outOfStockMedicines->count() }}</span>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($outOfStockMedicines as $medicine)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('medicines.show', $medicine) }}" class="text-decoration-none">{{ $medicine->medicine_name }}</a>
                        <span class="badge bg-danger-subtle text-danger">0 stock</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center py-4"><i class="bi bi-check-circle fs-3 d-block mb-2"></i>Nothing out of stock</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white"><i class="bi bi-trophy text-warning me-2"></i>Top Selling (This Month)</div>
            <ul class="list-group list-group-flush">
                @forelse ($topSellingMedicines as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="fw-medium">{{ $item->medicine->medicine_name ?? '-' }}</span>
                        <span class="badge bg-success-subtle text-success">{{ $item->total_qty }} sold</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>No sales yet this month</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const last30 = @json($salesLast30Days);
    const financialYear = @json($salesCurrentFinancialYear);

    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        },
        elements: { point: { radius: 2, hoverRadius: 5 }, line: { tension: 0.35 } }
    };

    new Chart(document.getElementById('salesLast30DaysChart'), {
        type: 'line',
        data: {
            labels: last30.labels,
            datasets: [{
                label: 'Sales',
                data: last30.data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,.08)',
                borderWidth: 2,
                fill: true,
            }],
        },
        options: chartDefaults,
    });

    new Chart(document.getElementById('salesCurrentFinancialYearChart'), {
        type: 'line',
        data: {
            labels: financialYear.labels,
            datasets: [{
                label: 'Sales',
                data: financialYear.data,
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,.08)',
                borderWidth: 2,
                fill: true,
            }],
        },
        options: chartDefaults,
    });
</script>
@endpush
