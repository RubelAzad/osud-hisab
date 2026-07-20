@extends('layouts.app')

@section('title', $purchase->invoice_no)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Purchase {{ $purchase->invoice_no }}</h4>
    @can('purchase_returns.create')
        <a href="{{ route('purchases.returns.create', $purchase) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-return-left"></i> Return Items
        </a>
    @endcan
</div>

<div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Supplier</div><div>{{ $purchase->supplier->name ?? '-' }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Date</div><div>{{ $purchase->purchase_date->format('Y-m-d') }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Total</div><div class="fw-semibold">{{ number_format($purchase->total, 2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Due</div><div class="fw-semibold">{{ number_format($purchase->due, 2) }}</div></div></div></div>
</div>

<div class="card">
    <div class="card-header bg-white">Items</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Medicine</th><th>Batch No</th><th>Qty</th><th>Purchase Price</th><th>Sale Price</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach ($purchase->items as $item)
                    <tr>
                        <td>{{ $item->medicine->medicine_name ?? '-' }}</td>
                        <td>{{ $item->medicineBatch->batch_no ?? '-' }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->purchase_price, 2) }}</td>
                        <td>{{ number_format($item->sale_price, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end">Subtotal</td>
                    <td>{{ number_format($purchase->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">Discount</td>
                    <td>{{ number_format($purchase->discount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">VAT + Tax</td>
                    <td>{{ number_format($purchase->vat + $purchase->tax, 2) }}</td>
                </tr>
                <tr class="fw-semibold">
                    <td colspan="5" class="text-end">Total</td>
                    <td>{{ number_format($purchase->total, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">Paid</td>
                    <td>{{ number_format($purchase->paid, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">Due</td>
                    <td>{{ number_format($purchase->due, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
