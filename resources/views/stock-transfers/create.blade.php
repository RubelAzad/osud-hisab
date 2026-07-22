@extends('layouts.app')

@section('title', 'New Stock Transfer')

@section('content')
<h4 class="mb-3">New Stock Transfer</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('stock-transfers.store') }}">
    @csrf
    <div class="card mb-3">
        <div class="card-body row">
            <div class="col-md-4 mb-3">
                <label class="form-label">From Location</label>
                <select name="from_location_id" class="form-select" required>
                    <option value="">Select</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ old('from_location_id', currentLocationId()) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">To Location</label>
                <select name="to_location_id" class="form-select" required>
                    <option value="">Select</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ old('to_location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Transfer Date</label>
                <input type="date" name="transfer_date" class="form-control" value="{{ old('transfer_date', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Note</label>
                <input type="text" name="note" class="form-control" value="{{ old('note') }}">
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            Items
            <button type="button" class="btn btn-sm btn-outline-primary" id="add-row"><i class="bi bi-plus-lg"></i> Add Row</button>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>Medicine</th><th style="width:120px">Qty</th><th></th></tr>
                </thead>
                <tbody id="items-body"></tbody>
            </table>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save Transfer</button>
    <a href="{{ route('stock-transfers.index') }}" class="btn btn-light">Cancel</a>
</form>
@endsection

@push('scripts')
<script>
const medicines = @json($medicinesJson);
let rowIndex = 0;

function medicineOptions() {
    return '<option value="">Select medicine</option>' + medicines.map(m => `<option value="${m.id}">${m.name}</option>`).join('');
}

function addRow() {
    const i = rowIndex++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><select name="items[${i}][medicine_id]" class="form-select form-select-sm" required>${medicineOptions()}</select></td>
        <td><input type="number" name="items[${i}][qty]" class="form-control form-control-sm" min="1" value="1" required></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-x"></i></button></td>
    `;
    document.getElementById('items-body').appendChild(tr);
}

document.getElementById('add-row').addEventListener('click', addRow);
document.getElementById('items-body').addEventListener('click', function (e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('tr').remove();
    }
});

addRow();
</script>
@endpush
