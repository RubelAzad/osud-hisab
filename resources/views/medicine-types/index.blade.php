@extends('layouts.app')

@section('title', 'Medicine Types')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-grid text-primary me-2"></i>Medicine Types
        <span class="idx-count">{{ $medicineTypes->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('medicine_types.create')
            <a href="{{ route('medicine-types.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Type</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-end">Medicines</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicineTypes as $medicineType)
                    <tr>
                        <td class="fw-semibold">{{ $medicineType->name }}</td>
                        <td class="text-end">{{ $medicineType->medicines_count }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('medicine_types.edit')
                                    <a href="{{ route('medicine-types.edit', $medicineType) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('medicine_types.delete')
                                    <form action="{{ route('medicine-types.destroy', $medicineType) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this type?')">
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
                                <i class="bi bi-grid"></i>
                                <p>No medicine types found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $medicineTypes->links() }}
@endsection
