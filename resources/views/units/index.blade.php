@extends('layouts.app')

@section('title', 'Units')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Units</h4>
    @can('units.create')
        <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Unit</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Short Name</th>
                    <th>Medicines</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($units as $unit)
                    <tr>
                        <td>{{ $unit->name }}</td>
                        <td>{{ $unit->short_name }}</td>
                        <td>{{ $unit->medicines_count }}</td>
                        <td class="text-end">
                            @can('units.edit')
                                <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('units.delete')
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this unit?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No units yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $units->links() }}</div>
@endsection
