@extends('layouts.pos')

@section('content')
<div class="d-flex h-100">
    <div class="pos-products flex-grow-1 p-3">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex gap-2 mb-3">
            <input type="text" id="search-box" class="form-control" placeholder="Search medicine by name...">
            <input type="text" id="barcode-box" class="form-control" placeholder="Scan barcode..." style="max-width:220px" autofocus>
        </div>

        <div class="row g-2" id="product-grid"></div>
    </div>

    <div class="pos-cart border-start" style="width: 400px;">
        <div class="d-flex flex-column h-100">
            <div class="p-3 border-bottom">
                <select id="customer-select" class="form-select form-select-sm">
                    <option value="">Walk-in Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-grow-1 overflow-auto p-2" id="cart-lines">
                <div class="text-muted text-center py-5" id="empty-cart-msg">Cart is empty<br>Click a product or scan a barcode</div>
            </div>

            <div class="p-3 border-top">
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal</span><span id="cart-subtotal">0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Discount</span>
                    <input type="number" id="cart-discount" class="form-control form-control-sm text-end" style="width:100px" value="0" min="0">
                </div>
                <div class="d-flex justify-content-between fs-5 fw-semibold border-top pt-2 mb-3">
                    <span>Total</span><span id="cart-total">0.00</span>
                </div>
                <button class="btn btn-success w-100 btn-lg" id="checkout-btn" disabled>
                    <i class="bi bi-cash-coin"></i> Checkout
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkout-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between fs-4 fw-semibold mb-3">
                    <span>Total Due</span><span id="modal-total">0.00</span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select id="payment-method" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="mobile_banking">Mobile Banking</option>
                        <option value="bank">Bank</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Cash Tendered</label>
                    <input type="number" step="0.01" id="tendered" class="form-control form-control-lg">
                </div>
                <div class="d-flex justify-content-between fs-5">
                    <span>Change Due</span><span id="change-due" class="fw-semibold text-success">0.00</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirm-checkout-btn">Confirm & Print Receipt</button>
            </div>
        </div>
    </div>
</div>

<form id="pos-form" method="POST" action="{{ route('pos.checkout') }}" class="d-none">
    @csrf
    <input type="hidden" name="sale_date" id="form-sale-date">
    <input type="hidden" name="customer_id" id="form-customer-id">
    <input type="hidden" name="payment_method" id="form-payment-method">
    <input type="hidden" name="discount" id="form-discount">
    <input type="hidden" name="paid" id="form-paid">
    <div id="form-items"></div>
</form>
@endsection

@push('scripts')
<script>
const medicines = @json($medicinesJson);
let cart = [];

function findMedicine(id) {
    return medicines.find(m => m.id == id);
}

function renderProductGrid(filter = '') {
    const grid = document.getElementById('product-grid');
    const term = filter.toLowerCase();
    const filtered = medicines.filter(m => m.name.toLowerCase().includes(term));

    grid.innerHTML = filtered.map(m => `
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card product-tile border" data-id="${m.id}">
                <div class="card-body p-2 text-center">
                    <div class="fw-semibold small">${m.name}</div>
                    <div class="text-muted small">${parseFloat(m.price).toFixed(2)}</div>
                    <div class="text-muted small">Stock: ${m.stock}</div>
                </div>
            </div>
        </div>
    `).join('');
}

function addToCart(medicineId, qty = 1) {
    const medicine = findMedicine(medicineId);
    if (!medicine) return;

    const existing = cart.find(c => c.medicine_id == medicineId);
    if (existing) {
        existing.qty += qty;
    } else {
        cart.push({ medicine_id: medicine.id, name: medicine.name, price: medicine.price, qty, discount: 0, stock: medicine.stock });
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-lines');
    const emptyMsg = document.getElementById('empty-cart-msg');

    if (cart.length === 0) {
        container.innerHTML = '';
        container.appendChild(emptyMsg);
        document.getElementById('checkout-btn').disabled = true;
    } else {
        document.getElementById('checkout-btn').disabled = false;
        container.innerHTML = cart.map((line, i) => `
            <div class="d-flex align-items-center border-bottom py-2">
                <div class="flex-grow-1">
                    <div class="small fw-semibold">${line.name}</div>
                    <div class="d-flex align-items-center gap-1 mt-1">
                        <input type="number" min="1" value="${line.qty}" class="form-control form-control-sm cart-qty" style="width:60px" data-index="${i}">
                        <span class="small text-muted">x ${parseFloat(line.price).toFixed(2)}</span>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">${(line.qty * line.price - line.discount).toFixed(2)}</div>
                    <button class="btn btn-sm btn-link text-danger p-0 cart-remove" data-index="${i}">Remove</button>
                </div>
            </div>
        `).join('');
    }

    recalculate();
}

function recalculate() {
    const subtotal = cart.reduce((sum, l) => sum + (l.qty * l.price - l.discount), 0);
    const discount = parseFloat(document.getElementById('cart-discount').value || 0);
    const total = Math.max(subtotal - discount, 0);
    document.getElementById('cart-subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('cart-total').textContent = total.toFixed(2);
    return { subtotal, discount, total };
}

document.getElementById('search-box').addEventListener('input', (e) => renderProductGrid(e.target.value));

document.getElementById('barcode-box').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        const code = e.target.value.trim();
        const medicine = medicines.find(m => m.barcode === code);
        if (medicine) {
            addToCart(medicine.id);
        }
        e.target.value = '';
    }
});

document.getElementById('product-grid').addEventListener('click', (e) => {
    const tile = e.target.closest('.product-tile');
    if (tile) addToCart(parseInt(tile.dataset.id));
});

document.getElementById('cart-lines').addEventListener('input', (e) => {
    if (e.target.classList.contains('cart-qty')) {
        const i = parseInt(e.target.dataset.index);
        cart[i].qty = Math.max(1, parseInt(e.target.value || 1));
        renderCart();
    }
});

document.getElementById('cart-lines').addEventListener('click', (e) => {
    if (e.target.classList.contains('cart-remove')) {
        const i = parseInt(e.target.dataset.index);
        cart.splice(i, 1);
        renderCart();
    }
});

document.getElementById('cart-discount').addEventListener('input', recalculate);

document.getElementById('checkout-btn').addEventListener('click', () => {
    const { total } = recalculate();
    document.getElementById('modal-total').textContent = total.toFixed(2);
    document.getElementById('tendered').value = total.toFixed(2);
    updateChange();
    new bootstrap.Modal(document.getElementById('checkout-modal')).show();
});

document.getElementById('tendered').addEventListener('input', updateChange);

function updateChange() {
    const total = parseFloat(document.getElementById('modal-total').textContent);
    const tendered = parseFloat(document.getElementById('tendered').value || 0);
    document.getElementById('change-due').textContent = Math.max(tendered - total, 0).toFixed(2);
}

document.getElementById('confirm-checkout-btn').addEventListener('click', () => {
    const { discount, total } = recalculate();
    const tendered = parseFloat(document.getElementById('tendered').value || 0);
    const paid = Math.min(tendered, total);

    document.getElementById('form-sale-date').value = new Date().toISOString().slice(0, 10);
    document.getElementById('form-customer-id').value = document.getElementById('customer-select').value;
    document.getElementById('form-payment-method').value = document.getElementById('payment-method').value;
    document.getElementById('form-discount').value = discount;
    document.getElementById('form-paid').value = paid;

    const itemsContainer = document.getElementById('form-items');
    itemsContainer.innerHTML = cart.map((line, i) => `
        <input type="hidden" name="items[${i}][medicine_id]" value="${line.medicine_id}">
        <input type="hidden" name="items[${i}][qty]" value="${line.qty}">
        <input type="hidden" name="items[${i}][price]" value="${line.price}">
        <input type="hidden" name="items[${i}][discount]" value="${line.discount}">
    `).join('');

    document.getElementById('pos-form').submit();
});

renderProductGrid();
</script>
@endpush
