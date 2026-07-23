@extends('layouts.app')

@section('title', 'Quotation #'.$quotation->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Quotation #{{ $quotation->id }}</h4>
    @if ($quotation->status === 'open')
        @can('quotations.create')
            <form method="POST" action="{{ route('quotations.convert', $quotation) }}" onsubmit="return confirm('Convert this quotation into a real sale? This will consume stock.')">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-arrow-right-circle"></i> Convert to Sale</button>
            </form>
        @endcan
    @endif
</div>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Customer</div><div>{{ $quotation->customer->name ?? 'Walk-in' }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Date</div><div>{{ $quotation->quotation_date->format('Y-m-d') }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Total</div><div class="fw-semibold">{{ number_format($quotation->total, 2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Status</div><div class="fw-semibold">{{ ucfirst($quotation->status) }}</div></div></div></div>
</div>

<div class="card">
    <div class="card-header bg-white">Items</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Medicine</th><th>Qty</th><th>Price</th><th>Discount</th><th>Total</th></tr>
            </thead>
            <tbody>
                @foreach ($quotation->items as $item)
                    <tr>
                        <td>{{ $item->medicine->medicine_name ?? '-' }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->discount, 2) }}</td>
                        <td>{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr><td colspan="4" class="text-end">Subtotal</td><td>{{ number_format($quotation->subtotal, 2) }}</td></tr>
                <tr><td colspan="4" class="text-end">Discount</td><td>{{ number_format($quotation->discount, 2) }}</td></tr>
                <tr><td colspan="4" class="text-end">VAT</td><td>{{ number_format($quotation->vat, 2) }}</td></tr>
                <tr class="fw-semibold"><td colspan="4" class="text-end">Total</td><td>{{ number_format($quotation->total, 2) }}</td></tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
