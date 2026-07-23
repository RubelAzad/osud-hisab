@extends('layouts.app')

@section('title', 'Purchase Returns')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-arrow-return-left text-primary me-2"></i>Purchase Returns
        <span class="idx-count">{{ $purchaseReturns->total() }}</span>
    </h4>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Purchase Invoice</th>
                    <th>Supplier</th>
                    <th>Return Date</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchaseReturns as $purchaseReturn)
                    <tr>
                        <td><a href="{{ route('purchase-returns.show', $purchaseReturn) }}">{{ $purchaseReturn->purchase->invoice_no ?? '-' }}</a></td>
                        <td>{{ $purchaseReturn->supplier->name ?? '-' }}</td>
                        <td>{{ $purchaseReturn->return_date->format('d M Y') }}</td>
                        <td class="text-end fw-semibold">{{ number_format($purchaseReturn->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="idx-empty">
                                <i class="bi bi-arrow-return-left"></i>
                                <p>No purchase returns found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $purchaseReturns->links() }}
@endsection
