@extends('layouts.app')

@section('title', 'Locations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Locations</h4>
    @can('locations.create')
        <a href="{{ route('locations.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Location</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Name</th><th>Phone</th><th>Batches</th><th>Default</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($locations as $location)
                    <tr>
                        <td>{{ $location->name }}</td>
                        <td>{{ $location->phone }}</td>
                        <td>{{ $location->medicine_batches_count }}</td>
                        <td>{{ $location->is_default ? 'Yes' : '' }}</td>
                        <td>
                            <span class="badge {{ $location->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $location->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            @can('locations.edit')
                                <a href="{{ route('locations.edit', $location) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No locations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
