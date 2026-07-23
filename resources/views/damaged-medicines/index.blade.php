@extends('layouts.app')

@section('title', 'Damaged Medicines')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-exclamation-triangle text-primary me-2"></i>Damaged Medicines
        <span class="idx-count">{{ $damagedMedicines->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('damaged_medicines.create')
            <a href="{{ route('damaged-medicines.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Record Damage</a>
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
                    <th class="text-end">Qty</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($damagedMedicines as $damaged)
                    <tr>
                        <td>{{ $damaged->created_at->format('d M Y') }}</td>
                        <td>{{ $damaged->medicineBatch->medicine->medicine_name ?? '-' }}</td>
                        <td>{{ $damaged->medicineBatch->batch_no ?? '-' }}</td>
                        <td class="text-end fw-semibold">{{ $damaged->qty }}</td>
                        <td class="text-muted-2">{{ Str::limit($damaged->reason, 50) ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="idx-empty">
                                <i class="bi bi-exclamation-triangle"></i>
                                <p>No damaged medicines recorded</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $damagedMedicines->links() }}
@endsection
