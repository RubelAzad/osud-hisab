@extends('layouts.app')

@section('title', $customer->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $customer->name }}</h4>
    <div>
        @can('payments.create')
            <a href="{{ route('customers.payments.create', $customer) }}" class="btn btn-primary btn-sm">Record Payment</a>
        @endcan
        @can('customers.edit')
            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
        @endcan
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted small">Phone</div><div>{{ $customer->phone ?: '-' }}</div></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted small">Email</div><div>{{ $customer->email ?: '-' }}</div></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
        <div class="text-muted small">{{ $customer->balance < 0 ? 'Credit (We Owe Them)' : 'Balance Due' }}</div>
        <div class="fw-semibold {{ $customer->balance < 0 ? 'text-success' : '' }}">{{ number_format(abs($customer->balance), 2) }}</div>
    </div></div></div>
</div>

<div class="card">
    <div class="card-header bg-white">Sale History</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Invoice</th><th>Date</th><th>Total</th><th>Paid</th><th>Due</th></tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td><a href="{{ route('sales.show', $sale) }}">{{ $sale->invoice_no }}</a></td>
                        <td>{{ $sale->sale_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($sale->total, 2) }}</td>
                        <td>{{ number_format($sale->paid, 2) }}</td>
                        <td>{{ number_format($sale->due, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No sales yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $sales->links() }}</div>

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
