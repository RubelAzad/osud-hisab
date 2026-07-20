@extends('layouts.app')

@section('title', 'Manufacturers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Manufacturers</h4>
    @can('manufacturers.create')
        <a href="{{ route('manufacturers.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Manufacturer</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Medicines</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($manufacturers as $manufacturer)
                    <tr>
                        <td>{{ $manufacturer->name }}</td>
                        <td>{{ $manufacturer->phone }}</td>
                        <td>{{ $manufacturer->email }}</td>
                        <td>{{ $manufacturer->medicines_count }}</td>
                        <td>
                            <span class="badge {{ $manufacturer->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $manufacturer->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            @can('manufacturers.edit')
                                <a href="{{ route('manufacturers.edit', $manufacturer) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('manufacturers.delete')
                                <form action="{{ route('manufacturers.destroy', $manufacturer) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this manufacturer?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No manufacturers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $manufacturers->links() }}</div>
@endsection
