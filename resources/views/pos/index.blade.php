@extends('layouts.pos')

@section('content')
<div class="pos-products">
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-2 mb-0" style="border-radius:.5rem;font-size:.85rem;">
            <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search Bar -->
    <div class="pos-search">
        <div class="input-icon barcode-wrap">
            <i class="bi bi-upc-scan"></i>
            <input type="text" id="barcode-box" placeholder="Scan barcode..." autofocus autocomplete="off">
        </div>
        <div class="input-icon">
            <i class="bi bi-search"></i>
            <input type="text" id="search-box" placeholder="Search medicine, barcode, category..." autocomplete="off">
        </div>
        <button class="btn-history" onclick="openHistory()" title="Recent Transactions">
            <i class="bi bi-clock-history"></i>
            <span class="d-none d-md-inline">History</span>
        </button>
    </div>

    <!-- Filter Dropdowns -->
    <div class="filter-bar">
        <div class="filter-dropdown" id="cat-dropdown">
            <button class="filter-btn" onclick="toggleDropdown('cat-dropdown')">
                <i class="bi bi-grid-3x3-gap"></i>
                <span id="cat-label">All Categories</span>
                <i class="bi bi-chevron-down filter-chevron"></i>
            </button>
            <div class="filter-menu">
                <div class="filter-search">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search category..." oninput="filterDropdownItems(this, 'cat-list')">
                </div>
                <div class="filter-options" id="cat-list">
                    <button class="filter-opt active" data-val="all" onclick="selectFilter('cat', 'all', 'All Categories', this)">
                        <i class="bi bi-grid-3x3-gap me-1"></i> All Categories
                    </button>
                    @foreach ($categories as $cat)
                        <button class="filter-opt" data-val="{{ $cat->id }}" onclick="selectFilter('cat', {{ $cat->id }}, '{{ addslashes($cat->name) }}', this)">
                            {{ $cat->name }}
                            <span class="filter-count-badge">{{ $cat->med_count }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="filter-dropdown" id="brand-dropdown">
            <button class="filter-btn" onclick="toggleDropdown('brand-dropdown')">
                <i class="bi bi-buildings"></i>
                <span id="brand-label">All Brands</span>
                <i class="bi bi-chevron-down filter-chevron"></i>
            </button>
            <div class="filter-menu">
                <div class="filter-search">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search brand..." oninput="filterDropdownItems(this, 'brand-list')">
                </div>
                <div class="filter-options" id="brand-list">
                    <button class="filter-opt active" data-val="all" onclick="selectFilter('brand', 'all', 'All Brands', this)">
                        <i class="bi bi-buildings me-1"></i> All Brands
                    </button>
                    @foreach ($manufacturers as $mfr)
                        <button class="filter-opt" data-val="{{ $mfr->id }}" onclick="selectFilter('brand', {{ $mfr->id }}, '{{ addslashes($mfr->name) }}', this)">
                            {{ $mfr->name }}
                            <span class="filter-count-badge">{{ $mfr->med_count }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="filter-dropdown" id="feat-dropdown">
            <button class="filter-btn" onclick="toggleDropdown('feat-dropdown')">
                <i class="bi bi-star"></i>
                <span id="feat-label">All Products</span>
                <i class="bi bi-chevron-down filter-chevron"></i>
            </button>
            <div class="filter-menu">
                <div class="filter-options" id="feat-list">
                    <button class="filter-opt active" data-val="all" onclick="selectFilter('feat', 'all', 'All Products', this)">
                        <i class="bi bi-box me-1"></i> All Products
                    </button>
                    <button class="filter-opt" data-val="instock" onclick="selectFilter('feat', 'instock', 'In Stock', this)">
                        <i class="bi bi-check-circle me-1"></i> In Stock
                    </button>
                    <button class="filter-opt" data-val="lowstock" onclick="selectFilter('feat', 'lowstock', 'Low Stock', this)">
                        <i class="bi bi-exclamation-triangle me-1"></i> Low Stock
                    </button>
                    <button class="filter-opt" data-val="newest" onclick="selectFilter('feat', 'newest', 'Newest', this)">
                        <i class="bi bi-clock-history me-1"></i> Newest
                    </button>
                </div>
            </div>
        </div>

        <span class="filter-count" id="product-count">0 products</span>
    </div>

    <!-- Product Grid -->
    <div class="pos-grid">
        <div class="pos-grid-inner" id="product-grid"></div>
    </div>
</div>

<!-- Cart Panel -->
<div class="pos-cart" id="posCart">
    <div class="cart-head">
        <div class="cart-head-top">
            <div class="cart-head-title">
                <i class="bi bi-cart3-fill"></i> Current Order
                <span class="cart-badge" id="cart-item-count">0</span>
            </div>
            <button class="btn btn-sm d-md-none" onclick="closeMobileCart()" style="padding:.2rem .4rem;font-size:.8rem;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <select id="customer-select">
            <option value="">Walk-in Customer</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="cart-body" id="cart-lines">
        <div class="cart-empty" id="empty-cart-msg">
            <i class="bi bi-cart-x"></i>
            <p>Cart is empty</p>
            <p style="font-size:.72rem;color:#cbd5e1;">Tap a product to add</p>
        </div>
    </div>

    <div class="cart-foot">
        <div class="cart-sum-row">
            <span>Subtotal</span>
            <span id="cart-subtotal" class="fw-semibold">0.00</span>
        </div>
        <div class="cart-sum-row discount-row">
            <span><i class="bi bi-tag me-1"></i>Discount</span>
            <input type="number" id="cart-discount" value="0" min="0" step="0.01">
        </div>
        <div class="cart-grand">
            <span>Total</span>
            <span id="cart-total">0.00</span>
        </div>
        <button class="btn-pay" id="checkout-btn" disabled>
            <i class="bi bi-cash-stack"></i> Proceed to Payment
        </button>
    </div>
</div>

<!-- Mobile FAB -->
<button class="mobile-cart-fab" id="cart-toggle-btn" onclick="openMobileCart()">
    <i class="bi bi-cart3"></i>
    <span class="fab-badge" id="mobile-cart-badge" style="display:none;">0</span>
</button>

<!-- Recent Transactions Modal -->
<div class="modal fade" id="history-modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:.75rem;overflow:hidden;">
            <div class="modal-header" style="background:#0f172a;color:#fff;border:none;">
                <h6 class="modal-title fw-bold"><i class="bi bi-clock-history me-2"></i>Recent POS Transactions</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:brightness(0) invert(1);"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th style="font-size:.75rem;">Invoice</th>
                                <th style="font-size:.75rem;">Customer</th>
                                <th style="font-size:.75rem;" class="text-center">Items</th>
                                <th style="font-size:.75rem;" class="text-end">Total</th>
                                <th style="font-size:.75rem;" class="text-end">Paid</th>
                                <th style="font-size:.75rem;" class="text-end">Due</th>
                                <th style="font-size:.75rem;">Method</th>
                                <th style="font-size:.75rem;">Date</th>
                                <th style="font-size:.75rem;" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="history-body">
                            @forelse ($recentSales as $sale)
                                <tr>
                                    <td><span class="fw-semibold" style="font-size:.8rem;">{{ $sale['invoice_no'] }}</span></td>
                                    <td style="font-size:.8rem;">{{ $sale['customer'] }}</td>
                                    <td class="text-center"><span class="badge bg-light text-dark">{{ $sale['items_count'] }}</span></td>
                                    <td class="text-end fw-semibold" style="font-size:.8rem;">{{ number_format($sale['total'], 2) }}</td>
                                    <td class="text-end" style="font-size:.8rem;color:#16a34a;">{{ number_format($sale['paid'], 2) }}</td>
                                    <td class="text-end" style="font-size:.8rem;color:{{ $sale['due'] > 0 ? '#ef4444' : '#64748b' }};">{{ number_format($sale['due'], 2) }}</td>
                                    <td><span class="badge bg-light text-dark" style="font-size:.7rem;">{{ ucfirst(str_replace('_',' ',$sale['payment_method'])) }}</span></td>
                                    <td style="font-size:.75rem;color:#64748b;">{{ $sale['date'] }}<br>{{ $sale['time'] }}</td>
                                    <td class="text-center">
                                        @if ($sale['due'] > 0)
                                            <span class="badge" style="background:#fef3c7;color:#d97706;font-size:.65rem;">Due</span>
                                        @else
                                            <span class="badge" style="background:#dcfce7;color:#16a34a;font-size:.65rem;">Paid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center text-muted py-4">No recent transactions</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade checkout-modal" id="checkout-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold"><i class="bi bi-cash-stack me-2"></i>Complete Sale</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="checkout-total-box">
                    <div class="label">Total Due</div>
                    <div class="amount" id="modal-total">0.00</div>
                </div>

                <label class="form-label fw-semibold small mb-1">Payment Method</label>
                <div class="pay-methods mb-3">
                    <button type="button" class="pay-method active" data-method="cash" onclick="selectPayment(this)">
                        <i class="bi bi-cash"></i> Cash
                    </button>
                    <button type="button" class="pay-method" data-method="card" onclick="selectPayment(this)">
                        <i class="bi bi-credit-card"></i> Card
                    </button>
                    <button type="button" class="pay-method" data-method="mobile_banking" onclick="selectPayment(this)">
                        <i class="bi bi-phone"></i> Mobile
                    </button>
                    <button type="button" class="pay-method" data-method="bank" onclick="selectPayment(this)">
                        <i class="bi bi-bank"></i> Bank
                    </button>
                </div>

                <label class="form-label fw-semibold small mb-1">Amount Tendered</label>
                <div class="tendered-wrap">
                    <input type="number" step="0.01" id="tendered" placeholder="0.00">
                </div>

                <div class="change-box ok" id="change-display">
                    Change: <span id="change-due">0.00</span>
                </div>
            </div>
            <div class="modal-body pt-0">
                <button type="button" class="btn-confirm" id="confirm-checkout-btn">
                    <i class="bi bi-check-circle me-1"></i> Confirm & Print
                </button>
                <button type="button" class="btn btn-light w-100 mt-2" data-bs-dismiss="modal" style="border-radius:.5rem;font-size:.85rem;">Cancel</button>
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

@push('styles')
<style>
    /* ── History Button ─────────────────────── */
    .btn-history {
        display: flex;
        align-items: center;
        gap: .3rem;
        padding: .5rem .8rem;
        border: 1.5px solid #e2e8f0;
        border-radius: .5rem;
        background: #f8fafc;
        color: #475569;
        font-size: .82rem;
        font-weight: 500;
        cursor: pointer;
        white-space: nowrap;
        transition: all .15s;
        flex-shrink: 0;
    }

    .btn-history:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }

    /* ── Filter Bar ─────────────────────────── */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .5rem 1rem;
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .filter-dropdown { position: relative; }

    .filter-btn {
        display: flex;
        align-items: center;
        gap: .35rem;
        padding: .4rem .7rem;
        border: 1.5px solid #e2e8f0;
        border-radius: .5rem;
        background: #f8fafc;
        color: #334155;
        font-size: .8rem;
        font-weight: 500;
        cursor: pointer;
        white-space: nowrap;
        transition: all .12s;
    }

    .filter-btn:hover { border-color: #3b82f6; background: #eff6ff; }
    .filter-btn.active { border-color: #3b82f6; background: #eff6ff; color: #3b82f6; }

    .filter-chevron {
        font-size: .65rem;
        transition: transform .2s;
    }

    .filter-dropdown.open .filter-chevron { transform: rotate(180deg); }

    .filter-menu {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 220px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: .625rem;
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        z-index: 200;
        overflow: hidden;
    }

    .filter-dropdown.open .filter-menu { display: block; }

    .filter-search {
        display: flex;
        align-items: center;
        gap: .4rem;
        padding: .5rem .6rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .filter-search i { color: #94a3b8; font-size: .85rem; }

    .filter-search input {
        border: none;
        outline: none;
        font-size: .8rem;
        width: 100%;
        background: transparent;
    }

    .filter-options {
        max-height: 240px;
        overflow-y: auto;
        padding: .25rem;
    }

    .filter-options::-webkit-scrollbar { width: 4px; }
    .filter-options::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }

    .filter-opt {
        display: flex;
        align-items: center;
        gap: .4rem;
        width: 100%;
        padding: .45rem .6rem;
        border: none;
        background: none;
        font-size: .8rem;
        color: #475569;
        cursor: pointer;
        border-radius: .375rem;
        text-align: left;
        transition: background .1s;
    }

    .filter-opt:hover { background: #f1f5f9; }

    .filter-opt.active {
        background: #eff6ff;
        color: #3b82f6;
        font-weight: 600;
    }

    .filter-count-badge {
        margin-left: auto;
        background: #f1f5f9;
        color: #64748b;
        font-size: .65rem;
        font-weight: 600;
        padding: .1rem .4rem;
        border-radius: 1rem;
        min-width: 20px;
        text-align: center;
    }

    .filter-opt.active .filter-count-badge { background: #dbeafe; color: #3b82f6; }

    .filter-count {
        font-size: .72rem;
        color: #94a3b8;
        white-space: nowrap;
        flex-shrink: 0;
        margin-left: auto;
    }

    /* ── History Table ──────────────────────── */
    #history-modal .table th { border-bottom: 1px solid #e2e8f0; }
    #history-modal .table td { border-bottom: 1px solid #f1f5f9; }

    @media (max-width: 575.98px) {
        .filter-bar { padding: .4rem .6rem; flex-wrap: wrap; gap: .35rem; }
        .filter-btn { padding: .35rem .55rem; font-size: .75rem; }
        .filter-menu { min-width: 180px; }
        .btn-history span { display: none; }
    }
</style>
@endpush

@push('scripts')
<script>
const medicines = @json($medicinesJson);
let cart = [];
let activeCategory = 'all';
let activeBrand = 'all';
let activeFeature = 'all';

/* ── Dropdown Toggle ──────────────────────── */
function toggleDropdown(id) {
    const dd = document.getElementById(id);
    const wasOpen = dd.classList.contains('open');

    // Close all dropdowns first
    document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('open'));

    if (!wasOpen) dd.classList.add('open');
}

// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.filter-dropdown')) {
        document.querySelectorAll('.filter-dropdown').forEach(d => d.classList.remove('open'));
    }
});

/* ── Dropdown Search ──────────────────────── */
function filterDropdownItems(input, listId) {
    const term = input.value.toLowerCase().trim();
    const list = document.getElementById(listId);
    list.querySelectorAll('.filter-opt').forEach(opt => {
        const text = opt.textContent.toLowerCase();
        opt.style.display = text.includes(term) ? '' : 'none';
    });
}

/* ── Select Filter ────────────────────────── */
function selectFilter(type, val, label, btn) {
    if (type === 'cat') {
        activeCategory = val;
        document.getElementById('cat-label').textContent = label;
        document.querySelectorAll('#cat-list .filter-opt').forEach(o => o.classList.remove('active'));
    } else if (type === 'brand') {
        activeBrand = val;
        document.getElementById('brand-label').textContent = label;
        document.querySelectorAll('#brand-list .filter-opt').forEach(o => o.classList.remove('active'));
    } else if (type === 'feat') {
        activeFeature = val;
        document.getElementById('feat-label').textContent = label;
        document.querySelectorAll('#feat-list .filter-opt').forEach(o => o.classList.remove('active'));
    }

    btn.classList.add('active');

    // Update button state
    const dropdown = btn.closest('.filter-dropdown');
    const filterBtn = dropdown.querySelector('.filter-btn');
    filterBtn.classList.toggle('active', val !== 'all');

    // Close dropdown and re-render
    dropdown.classList.remove('open');
    renderProductGrid(document.getElementById('search-box').value);
}

/* ── Product Grid ─────────────────────────── */
function renderProductGrid(filter = '') {
    const grid = document.getElementById('product-grid');
    const countEl = document.getElementById('product-count');
    const term = filter.toLowerCase().trim();

    let filtered = medicines.filter(m => {
        if (activeCategory !== 'all' && m.category_id != activeCategory) return false;
        if (activeBrand !== 'all' && m.manufacturer_id != activeBrand) return false;
        if (term) {
            return (m.name && m.name.toLowerCase().includes(term)) ||
                   (m.barcode && m.barcode.toLowerCase().includes(term)) ||
                   (m.category && m.category.toLowerCase().includes(term)) ||
                   (m.manufacturer && m.manufacturer.toLowerCase().includes(term));
        }
        return true;
    });

    // Feature filter
    if (activeFeature === 'instock') {
        filtered = filtered.filter(m => parseInt(m.stock ?? 0) > 0);
    } else if (activeFeature === 'lowstock') {
        filtered = filtered.filter(m => { const s = parseInt(m.stock ?? 0); return s > 0 && s <= 10; });
    } else if (activeFeature === 'newest') {
        filtered = [...filtered].reverse();
    }

    if (filtered.length === 0) {
        grid.innerHTML = '<div class="text-center text-muted py-5" style="grid-column:1/-1;"><i class="bi bi-search fs-1 d-block mb-2" style="opacity:.3;"></i>No medicines found</div>';
    } else {
        grid.innerHTML = filtered.map((m, idx) => {
            const stock = parseInt(m.stock ?? 0);
            const oos = stock <= 0;
            const low = stock > 0 && stock <= 10;

            const imgSection = m.image
                ? `<img class="med-img" src="${m.image}" alt="${m.name}" loading="lazy" onerror="this.outerHTML='<div class=\\'med-img-placeholder\\'><i class=\\'bi bi-capsule\\'></i></div>'">`
                : `<div class="med-img-placeholder"><i class="bi bi-capsule"></i></div>`;

            return `
            <div class="med-card ${getColorClass(idx)} ${oos ? 'oos' : ''}" data-id="${m.id}" title="${m.name}">
                ${oos ? '<div class="med-oos">Out</div>' : ''}
                ${imgSection}
                <div class="med-name">${m.name || 'Unnamed'}</div>
                <div class="med-meta">
                    <span class="med-cat">${m.category || ''}</span>
                    <span class="med-brand">${m.manufacturer || ''}</span>
                </div>
                <div class="med-price-row">
                    <div class="med-price">${parseFloat(m.price).toFixed(2)}</div>
                    <div class="med-stock ${low ? 'low' : ''}">${oos ? 'Out' : stock + ' left'}</div>
                </div>
            </div>`;
        }).join('');
    }

    countEl.textContent = filtered.length + ' product' + (filtered.length !== 1 ? 's' : '');
}

const colorClasses = ['cc1','cc2','cc3','cc4','cc5','cc6'];
function getColorClass(i) { return colorClasses[i % colorClasses.length]; }

/* ── Cart ─────────────────────────────────── */
function addToCart(medicineId, qty = 1) {
    const medicine = medicines.find(m => m.id == medicineId);
    if (!medicine) return;
    const stock = parseInt(medicine.stock ?? 0);
    if (stock <= 0) return;

    const existing = cart.find(c => c.medicine_id == medicineId);
    if (existing) {
        if (existing.qty < stock) existing.qty += qty;
    } else {
        cart.push({
            medicine_id: medicine.id,
            name: medicine.name,
            price: medicine.price,
            qty: Math.min(qty, stock),
            discount: 0,
            stock: stock
        });
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-lines');
    const checkoutBtn = document.getElementById('checkout-btn');
    const itemCount = document.getElementById('cart-item-count');
    const mobileBadge = document.getElementById('mobile-cart-badge');

    const totalItems = cart.reduce((sum, l) => sum + l.qty, 0);
    itemCount.textContent = totalItems;

    if (totalItems > 0) {
        mobileBadge.style.display = 'flex';
        mobileBadge.textContent = totalItems;
    } else {
        mobileBadge.style.display = 'none';
    }

    if (cart.length === 0) {
        container.innerHTML = `
            <div class="cart-empty">
                <i class="bi bi-cart-x"></i>
                <p>Cart is empty</p>
                <p style="font-size:.72rem;color:#cbd5e1;">Tap a product to add</p>
            </div>`;
        checkoutBtn.disabled = true;
    } else {
        checkoutBtn.disabled = false;
        container.innerHTML = cart.map((line, i) => `
        <div class="cart-row">
            <div class="cart-row-info">
                <div class="cart-row-name" title="${line.name}">${line.name}</div>
                <div class="cart-row-controls">
                    <button class="qty-btn" onclick="changeQty(${i}, -1)"><i class="bi bi-dash"></i></button>
                    <input type="number" min="1" max="${line.stock}" value="${line.qty}" class="cart-qty" data-index="${i}">
                    <button class="qty-btn" onclick="changeQty(${i}, 1)"><i class="bi bi-plus"></i></button>
                    <span class="unit-price">&times; ${parseFloat(line.price).toFixed(2)}</span>
                </div>
            </div>
            <div class="cart-row-right">
                <div class="cart-row-total">${(line.qty * line.price - line.discount).toFixed(2)}</div>
                <button class="cart-row-del" data-index="${i}"><i class="bi bi-trash3-fill"></i></button>
            </div>
        </div>
        `).join('');
    }

    recalculate();
}

function changeQty(index, delta) {
    const line = cart[index];
    const newQty = line.qty + delta;
    if (newQty < 1 || newQty > line.stock) return;
    line.qty = newQty;
    renderCart();
}

function recalculate() {
    const subtotal = cart.reduce((sum, l) => sum + (l.qty * l.price - l.discount), 0);
    const discount = parseFloat(document.getElementById('cart-discount').value || 0);
    const total = Math.max(subtotal - discount, 0);
    document.getElementById('cart-subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('cart-total').textContent = total.toFixed(2);
    return { subtotal, discount, total };
}

/* ── Search ───────────────────────────────── */
document.getElementById('search-box').addEventListener('input', (e) => renderProductGrid(e.target.value));

document.getElementById('barcode-box').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        const code = e.target.value.trim();
        if (code) {
            const medicine = medicines.find(m => m.barcode === code);
            if (medicine) {
                addToCart(medicine.id);
                e.target.value = '';
            } else {
                e.target.select();
            }
        }
    }
});

/* ── Product Click ────────────────────────── */
document.getElementById('product-grid').addEventListener('click', (e) => {
    const card = e.target.closest('.med-card');
    if (card && !card.classList.contains('oos')) {
        addToCart(parseInt(card.dataset.id));
    }
});

/* ── Cart Events ──────────────────────────── */
document.getElementById('cart-lines').addEventListener('input', (e) => {
    if (e.target.classList.contains('cart-qty')) {
        const i = parseInt(e.target.dataset.index);
        cart[i].qty = Math.min(cart[i].stock, Math.max(1, parseInt(e.target.value || 1)));
        renderCart();
    }
});

document.getElementById('cart-lines').addEventListener('click', (e) => {
    const btn = e.target.closest('.cart-row-del');
    if (btn) {
        cart.splice(parseInt(btn.dataset.index), 1);
        renderCart();
    }
});

document.getElementById('cart-discount').addEventListener('input', recalculate);

/* ── Mobile Cart ──────────────────────────── */
function openMobileCart() {
    document.getElementById('posCart').classList.add('open');
    document.getElementById('cartOverlay').classList.add('show');
}

function closeMobileCart() {
    document.getElementById('posCart').classList.remove('open');
    document.getElementById('cartOverlay').classList.remove('show');
}

/* ── History Modal ────────────────────────── */
function openHistory() {
    new bootstrap.Modal(document.getElementById('history-modal')).show();
}

/* ── Checkout ─────────────────────────────── */
function selectPayment(btn) {
    document.querySelectorAll('.pay-method').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

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
    const diff = tendered - total;
    const display = document.getElementById('change-display');

    if (diff >= 0) {
        display.className = 'change-box ok';
        display.innerHTML = `Change: <span id="change-due">${diff.toFixed(2)}</span>`;
    } else {
        display.className = 'change-box due';
        display.innerHTML = `Remaining: <span id="change-due">${Math.abs(diff).toFixed(2)}</span>`;
    }
}

document.getElementById('confirm-checkout-btn').addEventListener('click', () => {
    const { discount, total } = recalculate();
    const tendered = parseFloat(document.getElementById('tendered').value || 0);
    const paid = Math.min(tendered, total);
    const activeMethod = document.querySelector('.pay-method.active');

    document.getElementById('form-sale-date').value = new Date().toISOString().slice(0, 10);
    document.getElementById('form-customer-id').value = document.getElementById('customer-select').value;
    document.getElementById('form-payment-method').value = activeMethod ? activeMethod.dataset.method : 'cash';
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

/* ── Init ─────────────────────────────────── */
renderProductGrid();
</script>
@endpush
