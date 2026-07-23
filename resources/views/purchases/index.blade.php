@extends('layouts.app')

@section('title', 'Purchases')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-bag-check text-primary me-2"></i>Purchases
        <span class="idx-count">{{ $purchases->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('purchases.create')
            <a href="{{ route('purchases.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>New Purchase</a>
        @endcan
    </div>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Invoice #..." value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="supplier_id" class="form-select form-select-sm">
                <option value="">All Suppliers</option>
                @foreach(\App\Models\Supplier::where('status', true)->orderBy('name')->get() as $s)
                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="location_id" class="form-select form-select-sm">
                <option value="">All Locations</option>
                @foreach(\App\Models\Location::where('status', true)->orderBy('name')->get() as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
        </div>
        <div class="col-md-2">
            <select name="payment_status" class="form-select form-select-sm">
                <option value="">All Payment</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="due" {{ request('payment_status') === 'due' ? 'selected' : '' }}>Due</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Supplier</th>
                    <th>Date</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Paid</th>
                    <th class="text-end">Due</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $purchase)
                    <tr>
                        <td><a href="{{ route('purchases.show', $purchase) }}">{{ $purchase->invoice_no }}</a></td>
                        <td>{{ $purchase->supplier->name ?? '-' }}</td>
                        <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                        <td class="text-end fw-semibold">{{ number_format($purchase->total, 2) }}</td>
                        <td class="text-end">{{ number_format($purchase->paid, 2) }}</td>
                        <td class="text-end">
                            @if($purchase->due > 0)
                                <span class="text-danger fw-semibold">{{ number_format($purchase->due, 2) }}</span>
                            @else
                                <span class="text-success">0.00</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-bag-check"></i>
                                <p>No purchases found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $purchases->links() }}
@endsection
