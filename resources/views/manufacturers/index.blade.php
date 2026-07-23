@extends('layouts.app')

@section('title', 'Manufacturers')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-building text-primary me-2"></i>Manufacturers
        <span class="idx-count">{{ $manufacturers->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('manufacturers.create')
            <a href="{{ route('manufacturers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Manufacturer</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th class="text-end">Medicines</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($manufacturers as $manufacturer)
                    <tr>
                        <td class="fw-semibold">{{ $manufacturer->name }}</td>
                        <td>{{ $manufacturer->phone ?: '-' }}</td>
                        <td class="text-muted-2">{{ $manufacturer->email ?: '-' }}</td>
                        <td class="text-end">{{ $manufacturer->medicines_count }}</td>
                        <td>
                            <span class="badge {{ $manufacturer->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $manufacturer->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('manufacturers.edit')
                                    <a href="{{ route('manufacturers.edit', $manufacturer) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('manufacturers.delete')
                                    <form action="{{ route('manufacturers.destroy', $manufacturer) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this manufacturer?')">
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
                                <i class="bi bi-building"></i>
                                <p>No manufacturers found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $manufacturers->links() }}
@endsection
