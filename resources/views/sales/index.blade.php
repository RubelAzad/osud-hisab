@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Sales</h4>
    @can('sales.create')
        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Sale</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_no }}</a></td>
                        <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($sale->total, 2) }}</td>
                        <td>{{ number_format($sale->paid, 2) }}</td>
                        <td>{{ number_format($sale->due, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No sales yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $sales->links() }}</div>
@endsection
