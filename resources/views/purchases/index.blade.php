@extends('layouts.app')

@section('title', 'Purchases')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-bag-check text-primary me-2"></i>Purchases
        <span class="idx-count">{{ $purchases->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('purchases.create')
            <a href="{{ route('purchases.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Purchase</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->invoice_no }}</a></td>
                        <td>{{ $purchase->supplier->name ?? '-' }}</td>
                        <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                        <td class="text-end fw-semibold">{{ number_format($purchase->total, 2) }}</td>
                        <td class="text-end">{{ number_format($purchase->paid, 2) }}</td>
                        <td class="text-end">
                            @if($purchase->due > 0)
                                <span class="text-danger fw-semibold">{{ number_format($purchase->due, 2) }}</span>
                            @else
                                <span class="text-success">0.00</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-bag-check"></i>
                                <p>No purchases found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $purchases->links() }}
@endsection
