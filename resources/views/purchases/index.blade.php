@extends('layouts.app')

@section('title', 'Purchases')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Purchases</h4>
    @can('purchases.create')
        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Purchase</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->invoice_no }}</a></td>
                        <td>{{ $purchase->supplier->name ?? '-' }}</td>
                        <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($purchase->total, 2) }}</td>
                        <td>{{ number_format($purchase->paid, 2) }}</td>
                        <td>{{ number_format($purchase->due, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No purchases yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $purchases->links() }}</div>
@endsection
