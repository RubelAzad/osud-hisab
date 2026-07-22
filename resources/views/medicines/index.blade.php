@extends('layouts.app')

@section('title', 'Medicines')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Medicines</h4>
    <div class="d-flex gap-2">
        @can('medicines.view')
            <a href="{{ route('medicines.barcode-labels') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-upc-scan"></i> Print Labels</a>
            <a href="{{ route('medicines.export') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-file-earmark-excel"></i> Export</a>
        @endcan
        @can('medicines.create')
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#import-modal"><i class="bi bi-upload"></i> Import</button>
            <a href="{{ route('medicines.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Medicine</a>
        @endcan
    </div>
</div>

@can('medicines.create')
<div class="modal fade" id="import-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('medicines.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Medicines</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Upload an Excel/CSV file exported from this screen (or matching its columns). Category, Manufacturer, Generic, Unit, and Medicine Type are matched by name — unmatched rows are skipped.</p>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

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
