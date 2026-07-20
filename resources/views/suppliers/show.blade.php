@extends('layouts.app')

@section('title', $supplier->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $supplier->name }}</h4>
    <div>
        @can('payments.create')
            <a href="{{ route('suppliers.payments.create', $supplier) }}" class="btn btn-primary btn-sm">Record Payment</a>
        @endcan
        @can('suppliers.edit')
            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
        @endcan
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Phone</div><div>{{ $supplier->phone ?: '-' }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Email</div><div>{{ $supplier->email ?: '-' }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
        <div class="text-muted small">{{ $supplier->balance < 0 ? 'Credit (Supplier Owes Us)' : 'Balance Due' }}</div>
        <div class="fw-semibold {{ $supplier->balance < 0 ? 'text-success' : '' }}">{{ number_format(abs($supplier->balance), 2) }}</div>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Status</div><div>{{ $supplier->status ? 'Active' : 'Inactive' }}</div></div></div></div>
</div>

<div class="card">
    <div class="card-header bg-white">Purchase History</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Invoice</th><th>Date</th><th>Total</th><th>Paid</th><th>Due</th></tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->invoice_no }}</a></td>
                        <td>{{ $purchase->purchase_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($purchase->total, 2) }}</td>
                        <td>{{ number_format($purchase->paid, 2) }}</td>
                        <td>{{ number_format($purchase->due, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No purchases yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $purchases->links() }}</div>

<div class="card mt-4">
    <div class="card-header bg-white">Payment History</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>Amount</th><th>Method</th><th>Note</th></tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td class="text-capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                        <td class="text-muted">{{ $payment->note }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $payments->links() }}</div>
@endsection
