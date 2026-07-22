@extends('layouts.app')

@section('title', 'Stock Transfers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Stock Transfers</h4>
    @can('stock_transfers.create')
        <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Transfer</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>From</th><th>To</th><th>Note</th></tr>
            </thead>
            <tbody>
                @forelse ($stockTransfers as $transfer)
                    <tr>
                        <td><a href="{{ route('stock-transfers.show', $transfer) }}">{{ $transfer->transfer_date->format('Y-m-d') }}</a></td>
                        <td>{{ $transfer->fromLocation->name ?? '-' }}</td>
                        <td>{{ $transfer->toLocation->name ?? '-' }}</td>
                        <td class="text-muted">{{ Str::limit($transfer->note, 40) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No stock transfers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $stockTransfers->links() }}</div>
@endsection
