@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-truck text-primary me-2"></i>Suppliers
        <span class="idx-count">{{ $suppliers->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('suppliers.create')
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Supplier</a>
        @endcan
    </div>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search name, company, phone..." value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Phone</th>
                    <th class="text-end">Balance Due</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td><a href="{{ route('suppliers.show', $supplier) }}">{{ $supplier->name }}</a></td>
                        <td>{{ $supplier->company_name ?: '-' }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td class="text-end">
                            @if($supplier->balance < 0)
                                <span class="text-success fw-semibold">{{ number_format(abs($supplier->balance), 2) }}</span>
                                <small class="text-muted-2">(credit)</small>
                            @else
                                {{ number_format(abs($supplier->balance), 2) }}
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $supplier->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $supplier->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('suppliers.edit')
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('suppliers.delete')
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-truck"></i>
                                <p>No suppliers found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $suppliers->links() }}
@endsection
