@extends('layouts.app')

@section('title', 'Customer Groups')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-people text-primary me-2"></i>Customer Groups
        <span class="idx-count">{{ $customerGroups->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('customer_groups.create')
            <a href="{{ route('customer-groups.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Customer Group</a>
        @endcan
    </div>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search name..." value="{{ request('q') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('customer-groups.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-end">Customers</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customerGroups as $customerGroup)
                    <tr>
                        <td class="fw-semibold">{{ $customerGroup->name }}</td>
                        <td class="text-end">{{ $customerGroup->customers_count }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('customer_groups.edit')
                                    <a href="{{ route('customer-groups.edit', $customerGroup) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('customer_groups.delete')
                                    <form action="{{ route('customer-groups.destroy', $customerGroup) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer group?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="idx-empty">
                                <i class="bi bi-people"></i>
                                <p>No customer groups found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $customerGroups->links() }}
@endsection
