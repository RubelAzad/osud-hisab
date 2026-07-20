@extends('layouts.app')

@section('title', 'New Sale')

@section('content')
<h4 class="mb-3">New Sale</h4>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('sales.store') }}" id="sale-form">
    @csrf
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-select">
                        <option value="">Walk-in Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Sale Date</label>
                    <input type="date" name="sale_date" class="form-control" value="{{ old('sale_date', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="mobile_banking">Mobile Banking</option>
                        <option value="bank">Bank</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Note</label>
                    <input type="text" name="note" class="form-control" value="{{ old('note') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            Items
            <button type="button" class="btn btn-sm btn-outline-primary" id="add-row"><i class="bi bi-plus-lg"></i> Add Row</button>
        </div>
        <div class="table-responsive">
            <table class="table mb-0" id="items-table">
                <thead class="table-light">
                    <tr>
                        <th style="min-width:220px">Medicine</th>
                        <th style="width:100px">In Stock</th>
                        <th style="width:90px">Qty</th>
                        <th style="width:110px">Price</th>
                        <th style="width:110px">Discount</th>
                        <th style="width:100px">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="items-body"></tbody>
            </table>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span><span id="subtotal-display">0.00</span>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6">Discount</label>
                        <div class="col-6"><input type="number" step="0.01" name="discount" id="discount" class="form-control form-control-sm" value="{{ old('discount', 0) }}"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6">Paid</label>
                        <div class="col-6"><input type="number" step="0.01" name="paid" id="paid" class="form-control form-control-sm" value="{{ old('paid', 0) }}"></div>
                    </div>
                    <div class="d-flex justify-content-between fw-semibold border-top pt-2">
                        <span>Total (excl. VAT)</span><span id="subtotal-display-2">0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save Sale</button>
    <a href="{{ route('sales.index') }}" class="btn btn-light">Cancel</a>
</form>
@endsection

@push('scripts')
<script>
const medicines = @json($medicinesJson);
let rowIndex = 0;

function medicineOptions(selected) {
    return '<option value="">Select medicine</option>' + medicines.map(m =>
        `<option value="${m.id}" data-price="${m.price}" data-stock="${m.stock}" ${selected == m.id ? 'selected' : ''}>${m.name}</option>`
    ).join('');
}

function addRow() {
    const i = rowIndex++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><select name="items[${i}][medicine_id]" class="form-select form-select-sm medicine-select" required>${medicineOptions('')}</select></td>
        <td class="stock-display align-middle">-</td>
        <td><input type="number" name="items[${i}][qty]" class="form-control form-control-sm qty" min="1" value="1" required></td>
        <td><input type="number" step="0.01" name="items[${i}][price]" class="form-control form-control-sm price" required></td>
        <td><input type="number" step="0.01" name="items[${i}][discount]" class="form-control form-control-sm discount" value="0"></td>
        <td class="line-total align-middle">0.00</td>
        <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-x"></i></button></td>
    `;
    document.getElementById('items-body').appendChild(tr);
    recalculate();
}

document.getElementById('add-row').addEventListener('click', addRow);

document.getElementById('items-body').addEventListener('change', function (e) {
    if (e.target.classList.contains('medicine-select')) {
        const opt = e.target.selectedOptions[0];
        const row = e.target.closest('tr');
        if (opt && opt.value) {
            row.querySelector('.price').value = opt.dataset.price;
            row.querySelector('.stock-display').textContent = opt.dataset.stock;
        } else {
            row.querySelector('.stock-display').textContent = '-';
        }
    }
    recalculate();
});

document.getElementById('items-body').addEventListener('input', recalculate);

document.getElementById('items-body').addEventListener('click', function (e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('tr').remove();
        recalculate();
    }
});

['discount', 'paid'].forEach(id => document.getElementById(id).addEventListener('input', recalculate));

function recalculate() {
    let subtotal = 0;
    document.querySelectorAll('#items-body tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty')?.value || 0);
        const price = parseFloat(row.querySelector('.price')?.value || 0);
        const discount = parseFloat(row.querySelector('.discount')?.value || 0);
        const lineTotal = (qty * price) - discount;
        row.querySelector('.line-total').textContent = lineTotal.toFixed(2);
        subtotal += lineTotal;
    });
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const total = subtotal - discount;
    document.getElementById('subtotal-display').textContent = subtotal.toFixed(2);
    document.getElementById('subtotal-display-2').textContent = total.toFixed(2);
}

addRow();
</script>
@endpush
