@extends('layouts.app')

@section('title', 'Selling Price Groups')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-tags text-primary me-2"></i>Selling Price Groups
        <span class="idx-count">{{ $priceGroups->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('price_groups.create')
            <a href="{{ route('price-groups.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Price Group</a>
        @endcan
    </div>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search name..." value="{{ request('q') }}">
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
            <a href="{{ route('price-groups.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-end">Medicines Priced</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($priceGroups as $priceGroup)
                    <tr>
                        <td class="fw-semibold">{{ $priceGroup->name }}</td>
                        <td class="text-end">{{ $priceGroup->medicines_count }}</td>
                        <td>
                            <span class="badge {{ $priceGroup->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $priceGroup->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('price_groups.edit')
                                    <a href="{{ route('price-groups.prices', $priceGroup) }}" class="btn btn-outline-secondary">Set Prices</a>
                                    <a href="{{ route('price-groups.edit', $priceGroup) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('price_groups.delete')
                                    <form action="{{ route('price-groups.destroy', $priceGroup) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this price group?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="idx-empty">
                                <i class="bi bi-tags"></i>
                                <p>No price groups found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $priceGroups->links() }}
@endsection
