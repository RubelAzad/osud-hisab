@extends('layouts.app')

@section('title', 'Quotations')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-file-earmark-ruled text-primary me-2"></i>Quotations
        <span class="idx-count">{{ $quotations->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('quotations.create')
            <a href="{{ route('quotations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Quotation</a>
        @endcan
    </div>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>
        <div class="col-md-2">
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
            <a href="{{ route('quotations.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th class="text-end">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotations as $quotation)
                    <tr>
                        <td><a href="{{ route('quotations.show', $quotation) }}">#{{ $quotation->id }}</a></td>
                        <td>{{ $quotation->quotation_date->format('d M Y') }}</td>
                        <td>{{ $quotation->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $quotation->location->name ?? '-' }}</td>
                        <td class="text-end fw-semibold">{{ number_format($quotation->total, 2) }}</td>
                        <td>
                            <span class="badge {{ match($quotation->status) { 'converted' => 'badge-success', 'expired' => 'badge-inactive', default => 'badge-info' } }}">
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-file-earmark-ruled"></i>
                                <p>No quotations found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $quotations->links() }}
@endsection
