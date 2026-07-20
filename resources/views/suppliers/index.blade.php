@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Suppliers</h4>
    @can('suppliers.create')
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Supplier</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Phone</th>
                    <th>Balance Due</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $supplier)
                    <tr>
                        <td><a href="{{ route('suppliers.show', $supplier) }}">{{ $supplier->name }}</a></td>
                        <td>{{ $supplier->company_name }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td class="{{ $supplier->balance < 0 ? 'text-success' : '' }}">
                            {{ number_format(abs($supplier->balance), 2) }}{{ $supplier->balance < 0 ? ' (credit)' : '' }}
                        </td>
                        <td>
                            <span class="badge {{ $supplier->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $supplier->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            @can('suppliers.edit')
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('suppliers.delete')
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this supplier?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No suppliers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $suppliers->links() }}</div>
@endsection
