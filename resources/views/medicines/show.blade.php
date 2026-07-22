@extends('layouts.app')

@section('title', $medicine->medicine_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $medicine->medicine_name }} <span class="text-muted fs-6">{{ $medicine->strength }}</span></h4>
    @can('medicines.edit')
        <a href="{{ route('medicines.edit', $medicine) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
    @endcan
</div>

<div class="row mb-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row row-cols-2 g-3">
                    <div><div class="text-muted small">Category</div><div>{{ $medicine->category->name ?? '-' }}</div></div>
                    <div><div class="text-muted small">Manufacturer</div><div>{{ $medicine->manufacturer->name ?? '-' }}</div></div>
                    <div><div class="text-muted small">Generic</div><div>{{ $medicine->generic->name ?? '-' }}</div></div>
                    <div><div class="text-muted small">Type</div><div>{{ $medicine->medicineType->name ?? '-' }}</div></div>
                    <div><div class="text-muted small">Unit</div><div>{{ $medicine->unit->name ?? '-' }}</div></div>
                    <div><div class="text-muted small">Sale Price</div><div>{{ number_format($medicine->sale_price, 2) }}</div></div>
                    <div><div class="text-muted small">Purchase Price</div><div>{{ number_format($medicine->purchase_price, 2) }}</div></div>
                    <div><div class="text-muted small">Current Stock</div><div class="fw-semibold">{{ $medicine->total_stock }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-muted small mb-2">Barcode</div>
                @if ($medicine->barcode)
                    {!! \Milon\Barcode\Facades\DNS1DFacade::getBarcodeHTML($medicine->barcode, 'C128', 1.6, 60) !!}
                    <div class="small mt-1">{{ $medicine->barcode }}</div>
                @else
                    <div class="text-muted">No barcode set</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">Batch History</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Batch No</th>
                    <th>Location</th>
                    <th>Supplier</th>
                    <th>Qty</th>
                    <th>Remaining</th>
                    <th>Purchase Price</th>
                    <th>Sale Price</th>
                    <th>Expiry</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($batches as $batch)
                    <tr class="{{ $batch->expiry_date && $batch->expiry_date->isPast() ? 'table-danger' : '' }}">
                        <td>{{ $batch->batch_no }}</td>
                        <td>{{ $batch->location->name ?? '-' }}</td>
                        <td>{{ $batch->supplier->name ?? '-' }}</td>
                        <td>{{ $batch->quantity }}</td>
                        <td>{{ $batch->remaining_qty }}</td>
                        <td>{{ number_format($batch->purchase_price, 2) }}</td>
                        <td>{{ number_format($batch->sale_price, 2) }}</td>
                        <td>{{ $batch->expiry_date?->format('Y-m-d') ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No batches yet — purchase this medicine to create one.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $batches->links() }}</div>
@endsection
