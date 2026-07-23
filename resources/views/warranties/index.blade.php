@extends('layouts.app')

@section('title', 'Warranties')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-shield-check text-primary me-2"></i>Warranties
        <span class="idx-count">{{ $warranties->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('warranties.create')
            <a href="{{ route('warranties.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Warranty</a>
        @endcan
    </div>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="Search name..." value="{{ request('q') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('warranties.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Duration</th>
                    <th class="text-end">Medicines</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warranties as $warranty)
                    <tr>
                        <td class="fw-semibold">{{ $warranty->name }}</td>
                        <td>{{ $warranty->duration_days }} days</td>
                        <td class="text-end">{{ $warranty->medicines_count }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('warranties.edit')
                                    <a href="{{ route('warranties.edit', $warranty) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('warranties.delete')
                                    <form action="{{ route('warranties.destroy', $warranty) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this warranty?')">
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
                                <i class="bi bi-shield-check"></i>
                                <p>No warranties found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $warranties->links() }}
@endsection
