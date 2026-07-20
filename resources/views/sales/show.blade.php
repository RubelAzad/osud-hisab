@extends('layouts.app')

@section('title', $sale->invoice_no)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Sale {{ $sale->invoice_no }}</h4>
    @can('sale_returns.create')
        <a href="{{ route('sales.returns.create', $sale) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-return-left"></i> Return Items
        </a>
    @endcan
</div>

<div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Customer</div><div>{{ $sale->customer->name ?? 'Walk-in' }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Date</div><div>{{ $sale->sale_date->format('Y-m-d') }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Total</div><div class="fw-semibold">{{ number_format($sale->total, 2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Due</div><div class="fw-semibold">{{ number_format($sale->due, 2) }}</div></div></div></div>
</div>

<div class="card">
    <div class="card-header bg-white">Items</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Medicine</th><th>Batch No</th><th>Qty</th><th>Price</th><th>Discount</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td>{{ $item->medicine->medicine_name ?? '-' }}</td>
                        <td>{{ $item->medicineBatch->batch_no ?? '-' }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->discount, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end">Subtotal</td>
                    <td>{{ number_format($sale->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">Discount</td>
                    <td>{{ number_format($sale->discount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">VAT</td>
                    <td>{{ number_format($sale->vat, 2) }}</td>
                </tr>
                <tr class="fw-semibold">
                    <td colspan="5" class="text-end">Total</td>
                    <td>{{ number_format($sale->total, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">Paid</td>
                    <td>{{ number_format($sale->paid, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">Due</td>
                    <td>{{ number_format($sale->due, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
