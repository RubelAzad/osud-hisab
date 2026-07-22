@extends('layouts.app')

@section('title', 'Print Barcode Labels')

@section('content')
<h4 class="mb-3">Print Barcode Labels</h4>

<form method="GET" action="{{ route('medicines.barcode-labels.print') }}" target="_blank">
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Medicine</th><th>Barcode</th><th style="width:140px">Labels to Print</th></tr>
                </thead>
                <tbody>
                    @forelse ($medicines as $medicine)
                        <tr>
                            <td>{{ $medicine->medicine_name }} {{ $medicine->strength }}</td>
                            <td class="text-muted">{{ $medicine->barcode }}</td>
                            <td>
                                <input type="number" name="labels[{{ $medicine->id }}]" class="form-control form-control-sm" min="0" value="0">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No medicines yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-printer"></i> Generate Label Sheet</button>
</form>
@endsection
