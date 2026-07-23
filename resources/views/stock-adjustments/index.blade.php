@extends('layouts.app')

@section('title', 'Stock Adjustments')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-sliders text-primary me-2"></i>Stock Adjustments
        <span class="idx-count">{{ $stockAdjustments->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('stock_adjustments.create')
            <a href="{{ route('stock-adjustments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Adjust Stock</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Medicine</th>
                    <th>Batch No</th>
                    <th>Type</th>
                    <th class="text-end">Qty</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockAdjustments as $adjustment)
                    <tr>
                        <td>{{ $adjustment->created_at->format('d M Y') }}</td>
                        <td>{{ $adjustment->medicineBatch->medicine->medicine_name ?? '-' }}</td>
                        <td>{{ $adjustment->medicineBatch->batch_no ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $adjustment->type === 'increase' ? 'badge-success' : 'badge-danger' }}">
                                {{ ucfirst($adjustment->type) }}
                            </span>
                        </td>
                        <td class="text-end fw-semibold">{{ $adjustment->qty }}</td>
                        <td class="text-muted-2">{{ Str::limit($adjustment->reason, 50) ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-sliders"></i>
                                <p>No stock adjustments recorded</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $stockAdjustments->links() }}
@endsection
