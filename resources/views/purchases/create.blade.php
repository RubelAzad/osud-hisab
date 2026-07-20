@extends('layouts.app')

@section('title', 'New Purchase')

@section('content')
<h4 class="mb-3">New Purchase</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('purchases.store') }}" id="purchase-form">
    @csrf
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">Select supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Purchase Date</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-5 mb-3">
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
                        <th style="min-width:200px">Medicine</th>
                        <th>Batch No</th>
                        <th style="width:90px">Qty</th>
                        <th style="width:110px">Purchase Price</th>
                        <th style="width:110px">Sale Price</th>
                        <th style="width:150px">Expiry Date</th>
                        <th style="width:150px">Mfg Date</th>
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
                        <label class="col-6">VAT</label>
                        <div class="col-6"><input type="number" step="0.01" name="vat" id="vat" class="form-control form-control-sm" value="{{ old('vat', 0) }}"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6">Tax</label>
                        <div class="col-6"><input type="number" step="0.01" name="tax" id="tax" class="form-control form-control-sm" value="{{ old('tax', 0) }}"></div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-6">Paid</label>
                        <div class="col-6"><input type="number" step="0.01" name="paid" id="paid" class="form-control form-control-sm" value="{{ old('paid', 0) }}"></div>
                    </div>
                    <div class="d-flex justify-content-between fw-semibold border-top pt-2">
                        <span>Total</span><span id="total-display">0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save Purchase</button>
    <a href="{{ route('purchases.index') }}" class="btn btn-light">Cancel</a>
</form>
@endsection

@push('scripts')
<script>
const medicines = @json($medicinesJson);
let rowIndex = 0;

function medicineOptions(selected) {
    return '<option value="">Select medicine</option>' + medicines.map(m =>
        `<option value="${m.id}" data-purchase-price="${m.purchase_price}" data-sale-price="${m.sale_price}" ${selected == m.id ? 'selected' : ''}>${m.name}</option>`
    ).join('');
}

function addRow() {
    const i = rowIndex++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><select name="items[${i}][medicine_id]" class="form-select form-select-sm medicine-select" required>${medicineOptions('')}</select></td>
        <td><input type="text" name="items[${i}][batch_no]" class="form-control form-control-sm" required></td>
        <td><input type="number" name="items[${i}][qty]" class="form-control form-control-sm qty" min="1" value="1" required></td>
        <td><input type="number" step="0.01" name="items[${i}][purchase_price]" class="form-control form-control-sm purchase-price" required></td>
        <td><input type="number" step="0.01" name="items[${i}][sale_price]" class="form-control form-control-sm sale-price" required></td>
        <td><input type="date" name="items[${i}][expiry_date]" class="form-control form-control-sm"></td>
        <td><input type="date" name="items[${i}][manufacture_date]" class="form-control form-control-sm"></td>
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
            row.querySelector('.purchase-price').value = opt.dataset.purchasePrice;
            row.querySelector('.sale-price').value = opt.dataset.salePrice;
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

['discount', 'vat', 'tax', 'paid'].forEach(id => document.getElementById(id).addEventListener('input', recalculate));

function recalculate() {
    let subtotal = 0;
    document.querySelectorAll('#items-body tr').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty')?.value || 0);
        const price = parseFloat(row.querySelector('.purchase-price')?.value || 0);
        const lineTotal = qty * price;
        row.querySelector('.line-total').textContent = lineTotal.toFixed(2);
        subtotal += lineTotal;
    });
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const vat = parseFloat(document.getElementById('vat').value || 0);
    const tax = parseFloat(document.getElementById('tax').value || 0);
    const total = subtotal - discount + vat + tax;
    document.getElementById('subtotal-display').textContent = subtotal.toFixed(2);
    document.getElementById('total-display').textContent = total.toFixed(2);
}

addRow();
</script>
@endpush
