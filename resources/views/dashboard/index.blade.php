@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h4 class="mb-3">Dashboard</h4>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Today's Sales</div>
                <div class="fs-4 fw-semibold">{{ number_format($todaySales, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Today's Purchases</div>
                <div class="fs-4 fw-semibold">{{ number_format($todayPurchases, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Today's Profit</div>
                <div class="fs-4 fw-semibold {{ $todayProfit < 0 ? 'text-danger' : 'text-success' }}">{{ number_format($todayProfit, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between">
                <span>Low Stock Alert</span>
                <span class="badge bg-warning text-dark">{{ $lowStockMedicines->count() }}</span>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($lowStockMedicines as $medicine)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{ route('medicines.show', $medicine) }}">{{ $medicine->medicine_name }}</a>
                        <span class="text-muted">{{ $medicine->total_stock ?? 0 }} / min {{ $medicine->minimum_stock }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Nothing low on stock.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between">
                <span>Out of Stock</span>
                <span class="badge bg-danger">{{ $outOfStockMedicines->count() }}</span>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($outOfStockMedicines as $medicine)
                    <li class="list-group-item">
                        <a href="{{ route('medicines.show', $medicine) }}">{{ $medicine->medicine_name }}</a>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Nothing out of stock.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between">
                <span>Expiring Soon (30 days)</span>
                <span class="badge bg-warning text-dark">{{ $expiringBatches->count() }}</span>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($expiringBatches as $batch)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $batch->medicine->medicine_name ?? '-' }} <span class="text-muted">({{ $batch->batch_no }})</span></span>
                        <span class="text-muted">{{ $batch->expiry_date->format('Y-m-d') }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Nothing expiring soon.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white">Top Selling Medicines (this month)</div>
            <ul class="list-group list-group-flush">
                @forelse ($topSellingMedicines as $item)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $item->medicine->medicine_name ?? '-' }}</span>
                        <span class="text-muted">{{ $item->total_qty }} sold</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No sales yet this month.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
