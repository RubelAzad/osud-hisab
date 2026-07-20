@extends('layouts.app')

@section('title', 'Sale Returns')

@section('content')
<h4 class="mb-3">Sale Returns</h4>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Sale Invoice</th>
                    <th>Customer</th>
                    <th>Return Date</th>
                    <th>Refund Amount</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($saleReturns as $saleReturn)
                    <tr>
                        <td><a href="{{ route('sale-returns.show', $saleReturn) }}">{{ $saleReturn->sale->invoice_no ?? '-' }}</a></td>
                        <td>{{ $saleReturn->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $saleReturn->return_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($saleReturn->refund_amount, 2) }}</td>
                        <td class="text-muted">{{ Str::limit($saleReturn->reason, 40) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No sale returns yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $saleReturns->links() }}</div>
@endsection
