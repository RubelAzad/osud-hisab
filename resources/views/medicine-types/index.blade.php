@extends('layouts.app')

@section('title', 'Medicine Types')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Medicine Types</h4>
    @can('medicine_types.create')
        <a href="{{ route('medicine-types.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Type</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Medicines</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicineTypes as $medicineType)
                    <tr>
                        <td>{{ $medicineType->name }}</td>
                        <td>{{ $medicineType->medicines_count }}</td>
                        <td class="text-end">
                            @can('medicine_types.edit')
                                <a href="{{ route('medicine-types.edit', $medicineType) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('medicine_types.delete')
                                <form action="{{ route('medicine-types.destroy', $medicineType) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this type?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No medicine types yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $medicineTypes->links() }}</div>
@endsection
