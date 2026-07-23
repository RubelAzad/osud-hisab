@extends('layouts.app')

@section('title', 'Sale Returns')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-arrow-return-left text-primary me-2"></i>Sale Returns
        <span class="idx-count">{{ $saleReturns->total() }}</span>
    </h4>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <select name="customer_id" class="form-select form-select-sm">
                <option value="">All Customers</option>
                @foreach(\App\Models\Customer::orderBy('name')->get() as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
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
            <a href="{{ route('sale-returns.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Sale Invoice</th>
                    <th>Customer</th>
                    <th>Return Date</th>
                    <th class="text-end">Refund Amount</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($saleReturns as $saleReturn)
                    <tr>
                        <td><a href="{{ route('sale-returns.show', $saleReturn) }}">{{ $saleReturn->sale->invoice_no ?? '-' }}</a></td>
                        <td>{{ $saleReturn->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $saleReturn->return_date->format('d M Y') }}</td>
                        <td class="text-end fw-semibold">{{ number_format($saleReturn->refund_amount, 2) }}</td>
                        <td class="text-muted-2">{{ Str::limit($saleReturn->reason, 40) ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="idx-empty">
                                <i class="bi bi-arrow-return-left"></i>
                                <p>No sale returns found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $saleReturns->links() }}
@endsection
