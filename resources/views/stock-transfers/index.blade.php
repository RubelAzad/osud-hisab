@extends('layouts.app')

@section('title', 'Stock Transfers')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-arrow-left-right text-primary me-2"></i>Stock Transfers
        <span class="idx-count">{{ $stockTransfers->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('stock_transfers.create')
            <a href="{{ route('stock-transfers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Transfer</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockTransfers as $transfer)
                    <tr>
                        <td><a href="{{ route('stock-transfers.show', $transfer) }}">{{ $transfer->transfer_date->format('d M Y') }}</a></td>
                        <td>{{ $transfer->fromLocation->name ?? '-' }}</td>
                        <td>{{ $transfer->toLocation->name ?? '-' }}</td>
                        <td class="text-muted-2">{{ Str::limit($transfer->note, 40) ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="idx-empty">
                                <i class="bi bi-arrow-left-right"></i>
                                <p>No stock transfers found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $stockTransfers->links() }}
@endsection
