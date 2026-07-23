@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-credit-card text-primary me-2"></i>Payments
        <span class="idx-count">{{ $payments->total() }}</span>
    </h4>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Party</th>
                    <th class="text-end">Amount</th>
                    <th>Method</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('d M Y') }}</td>
                        <td>
                            @if ($payment->customer)
                                <a href="{{ route('customers.show', $payment->customer) }}">{{ $payment->customer->name }}</a>
                                <span class="badge badge-info ms-1">Customer</span>
                            @elseif ($payment->supplier)
                                <a href="{{ route('suppliers.show', $payment->supplier) }}">{{ $payment->supplier->name }}</a>
                                <span class="badge badge-secondary ms-1">Supplier</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end fw-semibold">{{ number_format($payment->amount, 2) }}</td>
                        <td><span class="text-capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</span></td>
                        <td class="text-muted-2">{{ Str::limit($payment->note, 40) ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="idx-empty">
                                <i class="bi bi-credit-card"></i>
                                <p>No payments found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $payments->links() }}
@endsection
