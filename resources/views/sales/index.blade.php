@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-receipt text-primary me-2"></i>Sales
        <span class="idx-count">{{ $sales->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('sales.create')
            <a href="{{ route('sales.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Sale</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_no }}</a></td>
                        <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $sale->location->name ?? '-' }}</td>
                        <td>{{ $sale->sale_date->format('d M Y') }}</td>
                        <td class="text-end fw-semibold">{{ number_format($sale->total, 2) }}</td>
                        <td class="text-end">{{ number_format($sale->paid, 2) }}</td>
                        <td class="text-end">
                            @if($sale->due > 0)
                                <span class="text-danger fw-semibold">{{ number_format($sale->due, 2) }}</span>
                            @else
                                <span class="text-success">0.00</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="idx-empty">
                                <i class="bi bi-receipt"></i>
                                <p>No sales found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $sales->links() }}
@endsection
