<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>POS - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #eef2f7;
        }

        /* ── Top Bar ───────────────────────────── */
        .pos-topbar {
            height: 54px;
            background: #0f172a;
            display: flex;
            align-items: center;
            padding: 0 1rem;
            gap: 1rem;
            flex-shrink: 0;
            z-index: 100;
        }

        .pos-topbar .brand {
            display: flex;
            align-items: center;
            gap: .5rem;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
        }

        .pos-topbar .brand i { font-size: 1.2rem; color: #3b82f6; }

        .pos-topbar .loc-badge {
            background: rgba(255,255,255,.08);
            color: #94a3b8;
            padding: .3rem .7rem;
            border-radius: .375rem;
            font-size: .75rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .pos-topbar .spacer { flex: 1; }

        .pos-topbar .back-btn {
            color: #94a3b8;
            text-decoration: none;
            font-size: .8rem;
            display: flex;
            align-items: center;
            gap: .3rem;
            padding: .3rem .6rem;
            border-radius: .375rem;
            transition: background .15s;
        }
        .pos-topbar .back-btn:hover { background: rgba(255,255,255,.08); color: #fff; }

        .pos-topbar .user-pill {
            display: flex;
            align-items: center;
            gap: .4rem;
            color: #cbd5e1;
            font-size: .8rem;
        }

        .pos-topbar .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: .75rem;
        }

        /* ── Body ──────────────────────────────── */
        .pos-body {
            display: flex;
            height: calc(100vh - 54px);
            overflow: hidden;
        }

        /* ── Products Area ─────────────────────── */
        .pos-products {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #eef2f7;
        }

        .pos-search {
            display: flex;
            gap: .5rem;
            padding: .6rem 1rem;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .pos-search .input-icon {
            position: relative;
            flex: 1;
        }

        .pos-search .input-icon i {
            position: absolute;
            left: .7rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .pos-search .input-icon input {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: .5rem;
            padding: .55rem .75rem .55rem 2.1rem;
            font-size: .875rem;
            background: #f8fafc;
            transition: all .15s;
        }

        .pos-search .input-icon input:focus {
            outline: none;
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,.1);
        }

        .pos-search .barcode-wrap {
            flex: 0 0 200px;
        }

        /* ── Product Grid ──────────────────────── */
        .pos-grid {
            flex: 1;
            overflow-y: auto;
            padding: .6rem;
        }

        .pos-grid::-webkit-scrollbar { width: 5px; }
        .pos-grid::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }

        .pos-grid-inner {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: .6rem;
        }

        .med-card {
            background: #fff;
            border: 1.5px solid #e8ecf1;
            border-radius: .65rem;
            padding: .65rem;
            cursor: pointer;
            user-select: none;
            transition: all .12s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .med-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 14px rgba(59,130,246,.12);
            transform: translateY(-2px);
        }

        .med-card:active {
            transform: scale(.97);
            box-shadow: none;
        }

        .med-card .med-img {
            width: 100%;
            aspect-ratio: 4/3;
            border-radius: .5rem;
            object-fit: cover;
            background: #f1f5f9;
            margin-bottom: .5rem;
        }

        .med-card .med-img-placeholder {
            width: 100%;
            aspect-ratio: 4/3;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .5rem;
            font-size: 1.8rem;
            opacity: .5;
        }

        .med-card.cc1 .med-img-placeholder { background: #dbeafe; color: #3b82f6; }
        .med-card.cc2 .med-img-placeholder { background: #fef3c7; color: #d97706; }
        .med-card.cc3 .med-img-placeholder { background: #ede9fe; color: #7c3aed; }
        .med-card.cc4 .med-img-placeholder { background: #dcfce7; color: #16a34a; }
        .med-card.cc5 .med-img-placeholder { background: #fce7f3; color: #db2777; }
        .med-card.cc6 .med-img-placeholder { background: #ffedd5; color: #ea580c; }

        .med-card .med-name {
            font-size: .82rem;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.3;
            margin-bottom: .2rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .med-card .med-meta {
            display: flex;
            gap: .25rem;
            flex-wrap: wrap;
            margin-bottom: .35rem;
        }

        .med-card .med-meta span {
            font-size: .65rem;
            padding: .1rem .35rem;
            border-radius: .25rem;
            background: #f1f5f9;
            color: #64748b;
            white-space: nowrap;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .med-card .med-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: auto;
            padding-top: .3rem;
            border-top: 1px solid #f1f5f9;
        }

        .med-card .med-price {
            font-size: .95rem;
            font-weight: 700;
            color: #2563eb;
        }

        .med-card .med-stock {
            font-size: .68rem;
            color: #64748b;
        }

        .med-card .med-stock.low { color: #f59e0b; font-weight: 600; }

        .med-card .med-oos {
            position: absolute;
            top: 0;
            right: 0;
            background: #ef4444;
            color: #fff;
            font-size: .6rem;
            font-weight: 700;
            padding: .15rem .45rem;
            border-radius: 0 .65rem 0 .4rem;
            text-transform: uppercase;
            letter-spacing: .03em;
            z-index: 2;
        }

        .med-card.oos {
            opacity: .45;
            pointer-events: none;
        }

        /* ── Cart Panel ────────────────────────── */
        .pos-cart {
            width: 370px;
            background: #fff;
            border-left: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .cart-head {
            padding: .65rem .9rem;
            border-bottom: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .cart-head-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .5rem;
        }

        .cart-head-title {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-weight: 700;
            font-size: .9rem;
            color: #0f172a;
        }

        .cart-head-title i { color: #3b82f6; }

        .cart-badge {
            background: #3b82f6;
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: .15rem .45rem;
            border-radius: 1rem;
            min-width: 20px;
            text-align: center;
        }

        .cart-head select {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: .4rem;
            padding: .4rem .6rem;
            font-size: .8rem;
            background: #f8fafc;
        }

        .cart-head select:focus { outline: none; border-color: #3b82f6; }

        /* Cart Items */
        .cart-body {
            flex: 1;
            overflow-y: auto;
            padding: .4rem;
        }

        .cart-body::-webkit-scrollbar { width: 4px; }
        .cart-body::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 2px; }

        .cart-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #94a3b8;
            text-align: center;
            gap: .5rem;
        }

        .cart-empty i { font-size: 2.5rem; opacity: .4; }
        .cart-empty p { font-size: .82rem; margin: 0; }

        .cart-row {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            padding: .55rem .4rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .cart-row:last-child { border-bottom: none; }

        .cart-row-info { flex: 1; min-width: 0; }

        .cart-row-name {
            font-size: .8rem;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cart-row-controls {
            display: flex;
            align-items: center;
            gap: .3rem;
            margin-top: .3rem;
        }

        .cart-row-controls .qty-btn {
            width: 24px;
            height: 24px;
            border: 1px solid #e2e8f0;
            border-radius: .3rem;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: .7rem;
            color: #475569;
            transition: background .1s;
        }

        .cart-row-controls .qty-btn:hover { background: #e2e8f0; }

        .cart-row-controls input {
            width: 40px;
            border: 1px solid #e2e8f0;
            border-radius: .3rem;
            padding: .15rem .2rem;
            font-size: .78rem;
            text-align: center;
        }

        .cart-row-controls input:focus { outline: none; border-color: #3b82f6; }

        .cart-row-controls .unit-price {
            font-size: .7rem;
            color: #94a3b8;
        }

        .cart-row-right {
            text-align: right;
            flex-shrink: 0;
        }

        .cart-row-total {
            font-size: .82rem;
            font-weight: 700;
            color: #1e293b;
        }

        .cart-row-del {
            background: none;
            border: none;
            color: #ef4444;
            font-size: .7rem;
            cursor: pointer;
            padding: .1rem;
            opacity: .5;
            margin-top: .15rem;
        }

        .cart-row-del:hover { opacity: 1; }

        /* Cart Footer */
        .cart-foot {
            border-top: 1px solid #e2e8f0;
            padding: .65rem .9rem;
            flex-shrink: 0;
        }

        .cart-sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: .3rem;
            font-size: .82rem;
            color: #475569;
        }

        .cart-sum-row.discount-row {
            align-items: center;
        }

        .cart-sum-row.discount-row input {
            width: 80px;
            border: 1.5px solid #e2e8f0;
            border-radius: .35rem;
            padding: .25rem .4rem;
            font-size: .78rem;
            text-align: right;
        }

        .cart-sum-row.discount-row input:focus { outline: none; border-color: #3b82f6; }

        .cart-grand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .5rem 0;
            margin: .3rem 0;
            border-top: 2px solid #0f172a;
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
        }

        .btn-pay {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            width: 100%;
            padding: .7rem;
            border: none;
            border-radius: .5rem;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            font-size: .95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
        }

        .btn-pay:hover { box-shadow: 0 4px 14px rgba(34,197,94,.35); transform: translateY(-1px); }
        .btn-pay:active { transform: scale(.98); }
        .btn-pay:disabled { opacity: .45; cursor: not-allowed; transform: none; box-shadow: none; }

        /* ── Mobile Cart Toggle ────────────────── */
        .mobile-cart-fab {
            display: none;
            position: fixed;
            bottom: 1.25rem;
            right: 1.25rem;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(34,197,94,.4);
            z-index: 90;
            align-items: center;
            justify-content: center;
        }

        .mobile-cart-fab .fab-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        .cart-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.5);
            z-index: 99;
        }

        .cart-overlay.show { display: block; }

        /* ── Checkout Modal ────────────────────── */
        .checkout-modal .modal-content {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
        }

        .checkout-modal .modal-header {
            background: #0f172a;
            color: #fff;
            border: none;
            padding: 1rem 1.25rem;
        }

        .checkout-modal .modal-header .btn-close { filter: brightness(0) invert(1); }

        .checkout-modal .modal-body { padding: 1.25rem; }

        .checkout-total-box {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
            border-radius: .75rem;
            padding: 1.25rem;
            text-align: center;
            margin-bottom: 1.25rem;
        }

        .checkout-total-box .label {
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            margin-bottom: .25rem;
        }

        .checkout-total-box .amount {
            font-size: 2.25rem;
            font-weight: 800;
        }

        .pay-methods {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .5rem;
            margin-bottom: 1rem;
        }

        .pay-method {
            border: 2px solid #e2e8f0;
            border-radius: .5rem;
            padding: .65rem;
            background: #fff;
            cursor: pointer;
            text-align: center;
            font-size: .8rem;
            font-weight: 500;
            color: #475569;
            transition: all .12s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .2rem;
        }

        .pay-method i { font-size: 1.15rem; }
        .pay-method:hover { border-color: #3b82f6; color: #3b82f6; }
        .pay-method.active { border-color: #3b82f6; background: rgba(59,130,246,.06); color: #3b82f6; }

        .tendered-wrap input {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: .5rem;
            padding: .75rem;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
        }

        .tendered-wrap input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.1); }

        .change-box {
            text-align: center;
            padding: .6rem;
            border-radius: .5rem;
            margin-top: .6rem;
            font-weight: 600;
        }

        .change-box.ok { background: #dcfce7; color: #16a34a; }
        .change-box.due { background: #fef2f2; color: #ef4444; }

        .btn-confirm {
            width: 100%;
            padding: .75rem;
            border: none;
            border-radius: .5rem;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: box-shadow .15s;
        }

        .btn-confirm:hover { box-shadow: 0 4px 14px rgba(34,197,94,.35); }

        /* ── Responsive ────────────────────────── */
        @media (max-width: 991.98px) {
            .pos-cart {
                position: fixed;
                top: 54px;
                right: 0;
                bottom: 0;
                width: 100% !important;
                max-width: 400px;
                transform: translateX(100%);
                transition: transform .3s cubic-bezier(.4,0,.2,1);
                z-index: 100;
                box-shadow: -8px 0 30px rgba(0,0,0,.15);
            }

            .pos-cart.open { transform: translateX(0); }

            .mobile-cart-fab { display: flex; }

            .pos-grid-inner {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .pos-topbar { padding: 0 .6rem; gap: .5rem; }
            .pos-topbar .loc-badge { display: none; }
            .pos-topbar .user-pill span { display: none; }
            .pos-topbar .back-btn span { display: none; }

            .pos-search { padding: .4rem .6rem; flex-wrap: wrap; }
            .pos-search .barcode-wrap { flex: 1 1 100%; order: -1; }

            .pos-grid-inner {
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
                gap: .45rem;
            }

            .med-card { padding: .5rem; }
            .med-card .med-name { font-size: .78rem; }
            .med-card .med-price { font-size: .85rem; }
            .med-card .med-img { aspect-ratio: 1; }

            .pos-cart { max-width: 100%; }

            .mobile-cart-fab { width: 50px; height: 50px; bottom: 1rem; right: 1rem; }
        }

        @media (min-width: 1400px) {
            .pos-grid-inner {
                grid-template-columns: repeat(auto-fill, minmax(185px, 1fr));
            }
            .pos-cart { width: 400px; }
        }

        @media (min-width: 1800px) {
            .pos-grid-inner {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="pos-topbar">
        <a href="{{ route('dashboard') }}" class="brand">
            <i class="bi bi-capsule-fill"></i>
            <span>{{ currentPharmacy()?->name ?? config('app.name') }}</span>
        </a>
        <div class="loc-badge">
            <i class="bi bi-geo-alt-fill"></i>
            {{ currentLocation()?->name ?? 'Main' }}
        </div>
        <div class="spacer"></div>
        <a href="{{ route('dashboard') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
            <span>Dashboard</span>
        </a>
        <div class="user-pill">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <span>{{ auth()->user()->name }}</span>
        </div>
    </nav>

    <div class="pos-body">
        @yield('content')
    </div>

    <div class="cart-overlay" id="cartOverlay" onclick="closeMobileCart()"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
