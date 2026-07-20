@extends('layouts.app')

@section('title', 'Sale Return')

@section('content')
<h4 class="mb-3">Sale Return — {{ $saleReturn->sale->invoice_no ?? '-' }}</h4>

<div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Customer</div><div>{{ $saleReturn->customer->name ?? 'Walk-in' }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Return Date</div><div>{{ $saleReturn->return_date->format('Y-m-d') }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Refund Amount</div><div class="fw-semibold">{{ number_format($saleReturn->refund_amount, 2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Reason</div><div>{{ $saleReturn->reason ?: '-' }}</div></div></div></div>
</div>

<div class="card">
    <div class="card-header bg-white">Returned Items</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Medicine</th><th>Batch No</th><th>Qty</th><th>Price</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach ($saleReturn->items as $item)
                    <tr>
                        <td>{{ $item->medicineBatch->medicine->medicine_name ?? '-' }}</td>
                        <td>{{ $item->medicineBatch->batch_no ?? '-' }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->qty * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
