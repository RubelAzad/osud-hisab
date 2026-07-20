@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<h4 class="mb-3">Payments</h4>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>Party</th><th>Amount</th><th>Method</th><th>Note</th></tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td>
                            @if ($payment->customer)
                                <a href="{{ route('customers.show', $payment->customer) }}">{{ $payment->customer->name }}</a> <span class="badge bg-info-subtle text-info">Customer</span>
                            @elseif ($payment->supplier)
                                <a href="{{ route('suppliers.show', $payment->supplier) }}">{{ $payment->supplier->name }}</a> <span class="badge bg-secondary-subtle text-secondary">Supplier</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td class="text-capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                        <td class="text-muted">{{ Str::limit($payment->note, 40) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $payments->links() }}</div>
@endsection
