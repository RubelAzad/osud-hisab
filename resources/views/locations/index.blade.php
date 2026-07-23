@extends('layouts.app')

@section('title', 'Locations')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-geo-alt text-primary me-2"></i>Locations
        <span class="idx-count">{{ $locations->count() }}</span>
    </h4>
    <div class="idx-actions">
        @can('locations.create')
            <a href="{{ route('locations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Location</a>
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
                    <th class="text-end">Batches</th>
                    <th>Default</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($locations as $location)
                    <tr>
                        <td class="fw-semibold">{{ $location->name }}</td>
                        <td>{{ $location->phone ?: '-' }}</td>
                        <td class="text-end">{{ $location->medicine_batches_count }}</td>
                        <td>
                            @if($location->is_default)
                                <span class="badge badge-info">Default</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $location->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $location->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('locations.edit')
                                    <a href="{{ route('locations.edit', $location) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-geo-alt"></i>
                                <p>No locations found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
