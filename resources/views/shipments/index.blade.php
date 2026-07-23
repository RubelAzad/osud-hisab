@extends('layouts.app')

@section('title', 'Shipments')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-box-seam text-primary me-2"></i>Shipments
        <span class="idx-count">{{ $shipments->total() }}</span>
    </h4>
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
