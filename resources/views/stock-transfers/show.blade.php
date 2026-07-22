@extends('layouts.app')

@section('title', 'Stock Transfer')

@section('content')
<h4 class="mb-3">Stock Transfer — {{ $stockTransfer->transfer_date->format('Y-m-d') }}</h4>

<div class="row mb-3">
    <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted small">From</div><div>{{ $stockTransfer->fromLocation->name ?? '-' }}</div></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted small">To</div><div>{{ $stockTransfer->toLocation->name ?? '-' }}</div></div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-muted small">Note</div><div>{{ $stockTransfer->note ?: '-' }}</div></div></div></div>
</div>

<div class="card">
    <div class="card-header bg-white">Items</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Medicine</th><th>Qty</th></tr>
            </thead>
            <tbody>
                @foreach ($stockTransfer->items as $item)
                    <tr>
                        <td>{{ $item->medicine->medicine_name ?? '-' }}</td>
                        <td>{{ $item->qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
