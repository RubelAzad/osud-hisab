@extends('layouts.app')

@section('title', 'Purchase Returns')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-arrow-return-left text-primary me-2"></i>Purchase Returns
        <span class="idx-count">{{ $purchaseReturns->total() }}</span>
    </h4>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <select name="supplier_id" class="form-select form-select-sm">
                <option value="">All Suppliers</option>
                @foreach(\App\Models\Supplier::where('status', true)->orderBy('name')->get() as $s)
                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('purchase-returns.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
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
