@extends('layouts.app')

@section('title', 'Discounts')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-percent text-primary me-2"></i>Discounts
        <span class="idx-count">{{ $discounts->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('discounts.create')
            <a href="{{ route('discounts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Discount</a>
        @endcan
    </div>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search name..." value="{{ request('q') }}">
        </div>
        <div class="col-md-2">
            <select name="type" class="form-select form-select-sm">
                <option value="">All Types</option>
                <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="applies_to" class="form-select form-select-sm">
                <option value="">All Scope</option>
                <option value="all" {{ request('applies_to') === 'all' ? 'selected' : '' }}>All Products</option>
                <option value="category" {{ request('applies_to') === 'category' ? 'selected' : '' }}>Category</option>
                <option value="medicine" {{ request('applies_to') === 'medicine' ? 'selected' : '' }}>Medicine</option>
            </select>
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
            <a href="{{ route('discounts.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th class="text-end">Value</th>
                    <th>Applies To</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($discounts as $discount)
                    <tr>
                        <td class="fw-semibold">{{ $discount->name }}</td>
                        <td class="text-capitalize">{{ $discount->type }}</td>
                        <td class="text-end fw-semibold">
                            {{ $discount->type === 'percentage' ? number_format($discount->value, 2).'%' : number_format($discount->value, 2) }}
                        </td>
                        <td>
                            @if ($discount->applies_to === 'category') {{ $discount->category->name ?? '-' }}
                            @elseif ($discount->applies_to === 'medicine') {{ $discount->medicine->medicine_name ?? '-' }}
                            @else All Products
                            @endif
                        </td>
                        <td class="text-muted-2 small">
                            {{ $discount->starts_at?->format('d M Y') ?? 'Always' }} &ndash; {{ $discount->ends_at?->format('d M Y') ?? 'Always' }}
                        </td>
                        <td>
                            <span class="badge {{ $discount->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $discount->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('discounts.edit')
                                    <a href="{{ route('discounts.edit', $discount) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('discounts.delete')
                                    <form action="{{ route('discounts.destroy', $discount) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this discount?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="idx-empty">
                                <i class="bi bi-percent"></i>
                                <p>No discounts found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $discounts->links() }}
@endsection
