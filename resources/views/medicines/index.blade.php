@extends('layouts.app')

@section('title', 'Medicines')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Medicines</h4>
    @can('medicines.create')
        <a href="{{ route('medicines.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Medicine</a>
    @endcan
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="Search by name..." value="{{ request('q') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Manufacturer</th>
                    <th>Sale Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicines as $medicine)
                    <tr>
                        <td><a href="{{ route('medicines.show', $medicine) }}">{{ $medicine->medicine_name }}</a> <span class="text-muted small">{{ $medicine->strength }}</span></td>
                        <td>{{ $medicine->category->name ?? '-' }}</td>
                        <td>{{ $medicine->manufacturer->name ?? '-' }}</td>
                        <td>{{ number_format($medicine->sale_price, 2) }}</td>
                        <td>
                            {{ $medicine->total_stock ?? 0 }}
                            @if (($medicine->total_stock ?? 0) <= $medicine->minimum_stock)
                                <span class="badge bg-warning text-dark">Low</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $medicine->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $medicine->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            @can('medicines.edit')
                                <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('medicines.delete')
                                <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this medicine?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No medicines yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $medicines->links() }}</div>
@endsection
