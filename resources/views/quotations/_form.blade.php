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

<form method="POST" action="{{ $formAction }}">
    @csrf
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-{{ $locations->count() > 1 ? 3 : 4 }} mb-3">
                    <label class="form-label">Customer</label>
                    <select name="customer_id" class="form-select">
                        <option value="">Walk-in Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($locations->count() > 1)
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Location</label>
                        <select name="location_id" class="form-select" required>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id', currentLocationId()) == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-2 mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="quotation_date" class="form-control" value="{{ old('quotation_date', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3 mb-3">
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
                    <div class="d-flex justify-content-between fw-semibold border-top pt-2">
                        <span>Total (excl. VAT)</span><span id="subtotal-display-2">0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
    <a href="{{ $cancelRoute }}" class="btn btn-light">Cancel</a>
</form>

@push('scripts')
<script>
const medicines = @json($medicinesJson);
let rowIndex = 0;

function medicineOptions(selected) {
    return '<option value="">Select medicine</option>' + medicines.map(m =>
        `<option value="${m.id}" data-price="${m.price}" ${selected == m.id ? 'selected' : ''}>${m.name}</option>`
    ).join('');
}

function addRow() {
    const i = rowIndex++;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><select name="items[${i}][medicine_id]" class="form-select form-select-sm medicine-select" required>${medicineOptions('')}</select></td>
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

document.getElementById('discount').addEventListener('input', recalculate);

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
