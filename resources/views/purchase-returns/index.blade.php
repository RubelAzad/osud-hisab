@extends('layouts.app')

@section('title', 'Purchase Returns')

@section('content')
<h4 class="mb-3">Purchase Returns</h4>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Purchase Invoice</th>
                    <th>Supplier</th>
                    <th>Return Date</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchaseReturns as $purchaseReturn)
                    <tr>
                        <td><a href="{{ route('purchase-returns.show', $purchaseReturn) }}">{{ $purchaseReturn->purchase->invoice_no ?? '-' }}</a></td>
                        <td>{{ $purchaseReturn->supplier->name ?? '-' }}</td>
                        <td>{{ $purchaseReturn->return_date->format('Y-m-d') }}</td>
                        <td>{{ number_format($purchaseReturn->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No purchase returns yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $purchaseReturns->links() }}</div>
@endsection
