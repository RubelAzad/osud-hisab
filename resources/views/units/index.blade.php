@extends('layouts.app')

@section('title', 'Units')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-rulers text-primary me-2"></i>Units
        <span class="idx-count">{{ $units->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('units.create')
            <a href="{{ route('units.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Unit</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th class="text-end">Medicines</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($units as $unit)
                    <tr>
                        <td class="fw-semibold">{{ $unit->name }}</td>
                        <td>{{ $unit->short_name }}</td>
                        <td class="text-end">{{ $unit->medicines_count }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('units.edit')
                                    <a href="{{ route('units.edit', $unit) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('units.delete')
                                    <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this unit?')">
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
                                <i class="bi bi-rulers"></i>
                                <p>No units found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $units->links() }}
@endsection
