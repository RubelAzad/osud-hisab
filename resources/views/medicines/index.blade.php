@extends('layouts.app')

@section('title', 'Medicines')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-capsule-pill text-primary me-2"></i>Medicines
        <span class="idx-count">{{ $medicines->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('medicines.view')
            <a href="{{ route('medicines.barcode-labels') }}" class="btn btn-outline-secondary"><i class="bi bi-upc-scan me-1"></i>Print Labels</a>
            <a href="{{ route('medicines.export') }}" class="btn btn-outline-secondary"><i class="bi bi-file-earmark-excel me-1"></i>Export</a>
        @endcan
        @can('medicines.create')
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#import-modal"><i class="bi bi-upload me-1"></i>Import</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#import-opening-stock-modal"><i class="bi bi-box-seam me-1"></i>Import Stock</button>
            <a href="{{ route('medicines.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Medicine</a>
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
                    <p class="text-muted small">Upload an Excel/CSV file. Category, Manufacturer, Generic, Unit, and Medicine Type are matched by name.</p>
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

<div class="modal fade" id="import-opening-stock-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('medicines.import-opening-stock') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Opening Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">Columns: Barcode Or Medicine Name, Batch No, Quantity, Purchase Price, Sale Price, Expiry Date, Location.</p>
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

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5 col-sm-8">
            <input type="text" name="q" class="form-control" placeholder="Search by name, barcode..." value="{{ request('q') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Search</button>
        </div>
        @if(request('q'))
            <div class="col-auto">
                <a href="{{ route('medicines.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg me-1"></i>Clear</a>
            </div>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Manufacturer</th>
                    <th class="text-end">Sale Price</th>
                    <th class="text-end">Stock</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($medicines as $medicine)
                    <tr>
                        <td>
                            <a href="{{ route('medicines.show', $medicine) }}">{{ $medicine->medicine_name }}</a>
                            <small class="text-muted-2 ms-1">{{ $medicine->strength }}</small>
                        </td>
                        <td>{{ $medicine->category->name ?? '-' }}</td>
                        <td>{{ $medicine->manufacturer->name ?? '-' }}</td>
                        <td class="text-end fw-semibold">{{ number_format($medicine->sale_price, 2) }}</td>
                        <td class="text-end">
                            {{ $medicine->total_stock ?? 0 }}
                            @if (($medicine->total_stock ?? 0) <= $medicine->minimum_stock)
                                <span class="badge badge-pending ms-1">Low</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $medicine->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $medicine->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('medicines.edit')
                                    <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('medicines.delete')
                                    <form action="{{ route('medicines.destroy', $medicine) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this medicine?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="idx-empty">
                                <i class="bi bi-capsule-pill"></i>
                                <p>No medicines found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $medicines->links() }}
@endsection
