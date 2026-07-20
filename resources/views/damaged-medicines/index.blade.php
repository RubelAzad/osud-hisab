@extends('layouts.app')

@section('title', 'Damaged Medicines')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Damaged Medicines</h4>
    @can('damaged_medicines.create')
        <a href="{{ route('damaged-medicines.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Record Damage</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>Medicine</th><th>Batch No</th><th>Qty</th><th>Reason</th></tr>
            </thead>
            <tbody>
                @forelse ($damagedMedicines as $damaged)
                    <tr>
                        <td>{{ $damaged->created_at->format('Y-m-d') }}</td>
                        <td>{{ $damaged->medicineBatch->medicine->medicine_name ?? '-' }}</td>
                        <td>{{ $damaged->medicineBatch->batch_no ?? '-' }}</td>
                        <td>{{ $damaged->qty }}</td>
                        <td class="text-muted">{{ Str::limit($damaged->reason, 50) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No damaged medicines recorded yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $damagedMedicines->links() }}</div>
@endsection
