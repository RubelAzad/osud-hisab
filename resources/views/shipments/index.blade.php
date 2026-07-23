@extends('layouts.app')

@section('title', 'Shipments')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-box-seam text-primary me-2"></i>Shipments
        <span class="idx-count">{{ $shipments->total() }}</span>
    </h4>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2">
            <select name="shipping_status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="pending" {{ request('shipping_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="shipped" {{ request('shipping_status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('shipping_status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="payment_status" class="form-select form-select-sm">
                <option value="">All Payment</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="due" {{ request('payment_status') === 'due' ? 'selected' : '' }}>Due</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('shipments.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Invoice No</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Shipping Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shipments as $sale)
                    <tr>
                        <td>{{ $sale->sale_date->format('d M Y') }}</td>
                        <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_no }}</a></td>
                        <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->location->name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ match($sale->shipping_status) { 'delivered' => 'badge-success', 'shipped' => 'badge-info', default => 'badge-pending' } }}">
                                {{ ucfirst($sale->shipping_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $sale->due > 0 ? 'badge-pending' : 'badge-success' }}">
                                {{ $sale->due > 0 ? 'Due' : 'Paid' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-box-seam"></i>
                                <p>No shipments found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $shipments->links() }}
@endsection
