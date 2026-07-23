<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 72px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #f8fafc;
            --sidebar-border: rgba(255,255,255,.06);
            --topbar-height: 60px;
            --accent: #3b82f6;
            --body-bg: #f1f5f9;
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--body-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* ── Sidebar ───────────────────────────── */
        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1040;
            transition: width .25s ease, transform .25s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .app-sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1rem;
            text-decoration: none;
            color: #fff;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
            gap: .75rem;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
            flex-shrink: 0;
            width: 32px;
            text-align: center;
        }

        .sidebar-brand span {
            font-weight: 600;
            font-size: 1.05rem;
            transition: opacity .2s;
        }

        .collapsed .sidebar-brand span { opacity: 0; width: 0; }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: .5rem 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.1) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        .nav-section {
            padding: .75rem 1rem .25rem;
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(148,163,184,.6);
            white-space: nowrap;
            overflow: hidden;
        }

        .collapsed .nav-section { text-align: center; padding: .5rem 0; font-size: 0; }
        .collapsed .nav-section::after { content: '⋯'; font-size: .75rem; }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .5rem 1rem;
            margin: 1px .5rem;
            border-radius: .5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: .875rem;
            transition: background .15s, color .15s;
            white-space: nowrap;
            position: relative;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-text-active);
        }

        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active);
            color: var(--sidebar-text-active);
        }

        .sidebar-nav .nav-link.active::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            background: var(--accent);
            border-radius: 0 3px 3px 0;
        }

        .sidebar-nav .nav-link .nav-label {
            transition: opacity .2s;
        }

        .collapsed .sidebar-nav .nav-link .nav-label { opacity: 0; width: 0; overflow: hidden; }

        .sidebar-nav .nav-link .chevron {
            margin-left: auto;
            transition: transform .2s, opacity .2s;
            font-size: .75rem;
        }

        .collapsed .sidebar-nav .nav-link .chevron { opacity: 0; width: 0; }

        .sidebar-nav .collapse .nav-link {
            padding-left: 3rem;
            font-size: .82rem;
        }

        .collapsed .sidebar-nav .collapse .nav-link {
            padding-left: 0;
            justify-content: center;
            font-size: .75rem;
        }

        .sidebar-nav .nav-link .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent);
            margin-left: auto;
            flex-shrink: 0;
        }

        .collapsed .sidebar-nav .nav-link .badge-dot { display: none; }

        /* ── Sidebar Footer ────────────────────── */
        .sidebar-footer {
            border-top: 1px solid var(--sidebar-border);
            padding: .75rem;
            flex-shrink: 0;
        }

        .sidebar-footer .nav-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .5rem .75rem;
            border-radius: .5rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: .85rem;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }

        .sidebar-footer .nav-link:hover { background: var(--sidebar-hover); color: var(--sidebar-text-active); }
        .collapsed .sidebar-footer .nav-link .nav-label { opacity: 0; width: 0; overflow: hidden; }
        .collapsed .sidebar-footer .nav-link { justify-content: center; padding: .5rem; }

        /* ── Mobile Overlay ────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.show { display: block; }

        /* ── Main Content ──────────────────────── */
        .app-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left .25s ease;
            display: flex;
            flex-direction: column;
        }

        .app-main.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* ── Top Bar ───────────────────────────── */
        .app-topbar {
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            gap: .75rem;
        }

        .sidebar-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            border-radius: .5rem;
            cursor: pointer;
            color: #475569;
            font-size: 1.2rem;
            transition: background .15s;
        }

        .sidebar-toggle:hover { background: #f1f5f9; }

        .mobile-toggle {
            display: none;
        }

        .topbar-divider {
            width: 1px;
            height: 24px;
            background: #e2e8f0;
        }

        .topbar-search {
            position: relative;
            max-width: 280px;
        }

        .topbar-search input {
            border: 1px solid #e2e8f0;
            border-radius: .5rem;
            padding: .4rem .75rem .4rem 2.25rem;
            font-size: .85rem;
            background: #f8fafc;
            width: 100%;
            transition: border-color .15s, box-shadow .15s;
        }

        .topbar-search input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,.1);
            background: #fff;
        }

        .topbar-search i {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: .9rem;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .topbar-icon-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            border-radius: .5rem;
            cursor: pointer;
            color: #475569;
            font-size: 1.15rem;
            position: relative;
            transition: background .15s;
        }

        .topbar-icon-btn:hover { background: #f1f5f9; }

        .topbar-icon-btn .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            font-size: .6rem;
            padding: .2em .4em;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .35rem .75rem;
            border-radius: .5rem;
            cursor: pointer;
            transition: background .15s;
            text-decoration: none;
            color: #1e293b;
        }

        .topbar-user:hover { background: #f1f5f9; }

        .topbar-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: .8rem;
        }

        .topbar-user-info {
            line-height: 1.2;
        }

        .topbar-user-name {
            font-size: .85rem;
            font-weight: 600;
        }

        .topbar-user-role {
            font-size: .7rem;
            color: #64748b;
        }

        /* ── Content Area ──────────────────────── */
        .app-content {
            flex: 1;
            padding: 1.5rem;
        }

        /* ── Cards ─────────────────────────────── */
        .card {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.04), 0 1px 2px rgba(0,0,0,.03);
            transition: box-shadow .2s;
        }

        .card:hover { box-shadow: 0 4px 6px rgba(0,0,0,.05), 0 2px 4px rgba(0,0,0,.03); }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            font-size: .9rem;
            padding: 1rem 1.25rem;
        }

        .stat-card {
            border-left: 3px solid;
            border-radius: .75rem;
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: .65rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .stat-card .stat-label {
            font-size: .78rem;
            color: #64748b;
            font-weight: 500;
        }

        /* ── Tables ────────────────────────────── */
        .table { font-size: .85rem; }
        .table thead th { font-weight: 600; font-size: .78rem; text-transform: uppercase; letter-spacing: .03em; color: #64748b; border-bottom-width: 1px; }
        .table-hover tbody tr:hover { background: #f8fafc; }
        .table td, .table th { vertical-align: middle; }

        /* ── Page Header ───────────────────────── */
        .page-header {
            margin-bottom: 1.5rem;
        }

        .page-header h4 {
            font-weight: 700;
            font-size: 1.35rem;
            margin: 0;
        }

        .breadcrumb {
            font-size: .8rem;
            margin: 0;
        }

        /* ── Alert ─────────────────────────────── */
        .alert { border: none; border-radius: .75rem; font-size: .875rem; }

        /* ── Badges ────────────────────────────── */
        .badge { font-weight: 500; font-size: .72rem; }

        /* ── Buttons ───────────────────────────── */
        .btn { border-radius: .5rem; font-size: .85rem; font-weight: 500; }

        /* ── Responsive ────────────────────────── */
        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .app-sidebar.mobile-open {
                transform: translateX(0);
            }

            .app-sidebar.collapsed {
                width: var(--sidebar-width) !important;
            }

            .app-main,
            .app-main.sidebar-collapsed {
                margin-left: 0 !important;
            }

            .mobile-toggle { display: flex; }
            .desktop-toggle { display: none; }

            .topbar-search { display: none; }

            .topbar-user-info { display: none; }

            .app-content { padding: 1rem; }
        }

        @media (max-width: 575.98px) {
            .app-content { padding: .75rem; }

            .stat-card .stat-value { font-size: 1.2rem; }
            .stat-card .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; }

            .topbar-right .topbar-divider { display: none; }
        }

        /* ── Scrollbar ─────────────────────────── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ── Index Page ─────────────────────────── */
        .idx-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            margin-bottom: 1.25rem;
        }
        .idx-header h4 {
            font-weight: 700;
            font-size: 1.35rem;
            margin: 0;
        }
        .idx-header .idx-count {
            background: #e2e8f0;
            color: #475569;
            font-weight: 600;
            font-size: .7rem;
            padding: .25em .55em;
            border-radius: 999px;
            margin-left: .4rem;
            vertical-align: middle;
        }
        .idx-actions { display: flex; gap: .5rem; flex-wrap: wrap; }

        /* Filter bar */
        .idx-filters {
            background: #fff;
            border-radius: .75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
            padding: .75rem 1rem;
            margin-bottom: 1rem;
        }
        .idx-filters .form-select,
        .idx-filters .form-control {
            font-size: .83rem;
            border-color: #e2e8f0;
        }
        .idx-filters .form-select:focus,
        .idx-filters .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,.08);
        }

        /* Table enhancements */
        .idx-table thead th {
            background: #f8fafc;
            font-weight: 600;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            padding: .65rem .75rem;
        }
        .idx-table tbody td {
            padding: .65rem .75rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: .85rem;
            vertical-align: middle;
        }
        .idx-table tbody tr:last-child td { border-bottom: none; }
        .idx-table tbody tr { transition: background .15s; }
        .idx-table tbody tr:hover { background: #f8fafc; }

        /* Link column styling */
        .idx-table td a { color: var(--accent); text-decoration: none; font-weight: 500; }
        .idx-table td a:hover { text-decoration: underline; }

        /* Status badges */
        .badge-active { background: #dcfce7; color: #16a34a; }
        .badge-inactive { background: #f1f5f9; color: #64748b; }
        .badge-pending { background: #fef3c7; color: #d97706; }
        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-info { background: #dbeafe; color: #2563eb; }
        .badge-secondary { background: #f1f5f9; color: #64748b; }

        /* Action buttons */
        .idx-actions .btn { padding: .3rem .6rem; font-size: .78rem; }
        .idx-actions .btn i { font-size: .85rem; }

        /* Empty state */
        .idx-empty {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }
        .idx-empty i { font-size: 2.5rem; display: block; margin-bottom: .75rem; opacity: .5; }
        .idx-empty p { margin: 0; font-size: .9rem; }

        /* Pagination tweaks */
        .pagination .page-item .page-link {
            border: none;
            color: #475569;
            font-size: .82rem;
            padding: .4rem .65rem;
            border-radius: .375rem;
            margin: 0 2px;
            background: transparent;
            transition: background .15s, color .15s;
        }
        .pagination .page-item .page-link:hover {
            background: #f1f5f9;
            color: var(--accent);
        }
        .pagination .page-item.active .page-link {
            background: var(--accent);
            color: #fff;
        }
        .pagination .page-item.disabled .page-link {
            color: #cbd5e1;
        }

        /* Muted text */
        .text-muted-2 { color: #94a3b8; }

        /* ── Animations ────────────────────────── */
        .fade-in { animation: fadeIn .3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Sidebar -->
    <aside class="app-sidebar" id="appSidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <i class="bi bi-capsule"></i>
            <span>{{ currentPharmacy()?->name ?? config('app.name') }}</span>
        </a>

        <div class="sidebar-nav" id="sidebarNav">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="nav-label">Dashboard</span>
            </a>

            @php
                $navGroup = function (string $id, string $icon, string $label, array $routePatterns) {
                    $active = request()->routeIs(...$routePatterns);
                    return compact('id', 'icon', 'label', 'active');
                };
            @endphp

            @canany(['users.view','roles.view'])
                @php $g = $navGroup('nav-user-management', 'bi-people-fill', 'User Management', ['users.*','roles.*']) @endphp
                <div class="nav-section">{{ $g['label'] }}</div>
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('users.view')
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="bi bi-person"></i>
                            <span class="nav-label">Users</span>
                        </a>
                    @endcan
                    @can('roles.view')
                        <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-lock"></i>
                            <span class="nav-label">Roles</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            @canany(['suppliers.view','customers.view'])
                @php $g = $navGroup('nav-contacts', 'bi-people', 'Contacts', ['suppliers.*','customers.*']) @endphp
                <div class="nav-section">{{ $g['label'] }}</div>
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('suppliers.view')
                        <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                            <i class="bi bi-truck"></i>
                            <span class="nav-label">Suppliers</span>
                        </a>
                    @endcan
                    @can('customers.view')
                        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge"></i>
                            <span class="nav-label">Customers</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            @canany(['medicines.view','categories.view','manufacturers.view','generics.view','medicine_types.view','units.view'])
                @php $g = $navGroup('nav-products', 'bi-capsule-pill', 'Products', ['medicines.*','categories.*','manufacturers.*','generics.*','medicine-types.*','units.*']) @endphp
                <div class="nav-section">{{ $g['label'] }}</div>
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('medicines.view')
                        <a href="{{ route('medicines.index') }}" class="nav-link {{ request()->routeIs('medicines.index') ? 'active' : '' }}">
                            <i class="bi bi-list-ul"></i>
                            <span class="nav-label">All Products</span>
                        </a>
                    @endcan
                    @can('medicines.create')
                        <a href="{{ route('medicines.create') }}" class="nav-link {{ request()->routeIs('medicines.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span class="nav-label">Add Product</span>
                        </a>
                        <a href="{{ route('medicines.barcode-labels') }}" class="nav-link {{ request()->routeIs('medicines.barcode-labels*') ? 'active' : '' }}">
                            <i class="bi bi-upc-scan"></i>
                            <span class="nav-label">Print Labels</span>
                        </a>
                    @endcan
                    @can('categories.view')
                        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="bi bi-bookmark"></i>
                            <span class="nav-label">Categories</span>
                        </a>
                    @endcan
                    @can('manufacturers.view')
                        <a href="{{ route('manufacturers.index') }}" class="nav-link {{ request()->routeIs('manufacturers.*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i>
                            <span class="nav-label">Brands</span>
                        </a>
                    @endcan
                    @can('generics.view')
                        <a href="{{ route('generics.index') }}" class="nav-link {{ request()->routeIs('generics.*') ? 'active' : '' }}">
                            <i class="bi bi-tag"></i>
                            <span class="nav-label">Generics</span>
                        </a>
                    @endcan
                    @can('medicine_types.view')
                        <a href="{{ route('medicine-types.index') }}" class="nav-link {{ request()->routeIs('medicine-types.*') ? 'active' : '' }}">
                            <i class="bi bi-grid"></i>
                            <span class="nav-label">Medicine Types</span>
                        </a>
                    @endcan
                    @can('units.view')
                        <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
                            <i class="bi bi-rulers"></i>
                            <span class="nav-label">Units</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            @canany(['purchases.view','purchase_returns.view'])
                @php $g = $navGroup('nav-purchases', 'bi-bag-check', 'Purchases', ['purchases.*','purchase-returns.*']) @endphp
                <div class="nav-section">{{ $g['label'] }}</div>
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('purchases.view')
                        <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                            <i class="bi bi-list-check"></i>
                            <span class="nav-label">All Purchases</span>
                        </a>
                    @endcan
                    @can('purchases.create')
                        <a href="{{ route('purchases.create') }}" class="nav-link {{ request()->routeIs('purchases.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span class="nav-label">Add Purchase</span>
                        </a>
                    @endcan
                    @can('purchase_returns.view')
                        <a href="{{ route('purchase-returns.index') }}" class="nav-link {{ request()->routeIs('purchase-returns.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-return-left"></i>
                            <span class="nav-label">Purchase Returns</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            @canany(['sales.view','sale_returns.view','quotations.view','drafts.view'])
                @php $g = $navGroup('nav-sell', 'bi-cart-check-fill', 'Sell', ['sales.*','sale-returns.*','pos.*','quotations.*','drafts.*','shipments.*']) @endphp
                <div class="nav-section">{{ $g['label'] }}</div>
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('sales.view')
                        <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                            <i class="bi bi-receipt"></i>
                            <span class="nav-label">All Sales</span>
                        </a>
                    @endcan
                    @can('sales.create')
                        <a href="{{ route('sales.create') }}" class="nav-link {{ request()->routeIs('sales.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span class="nav-label">Add Sale</span>
                        </a>
                        <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                            <i class="bi bi-shop"></i>
                            <span class="nav-label">Point of Sale</span>
                        </a>
                    @endcan
                    @can('drafts.view')
                        <a href="{{ route('drafts.index') }}" class="nav-link {{ request()->routeIs('drafts.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-text"></i>
                            <span class="nav-label">Drafts</span>
                        </a>
                    @endcan
                    @can('quotations.view')
                        <a href="{{ route('quotations.index') }}" class="nav-link {{ request()->routeIs('quotations.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-ruled"></i>
                            <span class="nav-label">Quotations</span>
                        </a>
                    @endcan
                    @can('sale_returns.view')
                        <a href="{{ route('sale-returns.index') }}" class="nav-link {{ request()->routeIs('sale-returns.*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-return-left"></i>
                            <span class="nav-label">Sale Returns</span>
                        </a>
                    @endcan
                    @can('sales.view')
                        <a href="{{ route('shipments.index') }}" class="nav-link {{ request()->routeIs('shipments.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam"></i>
                            <span class="nav-label">Shipments</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            @canany(['stock_transfers.view','damaged_medicines.view','stock_adjustments.view'])
                <div class="nav-section">Inventory</div>
            @endcanany

            @can('stock_transfers.view')
                <a href="{{ route('stock-transfers.index') }}" class="nav-link {{ request()->routeIs('stock-transfers.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>
                    <span class="nav-label">Stock Transfers</span>
                </a>
            @endcan
            @can('damaged_medicines.view')
                <a href="{{ route('damaged-medicines.index') }}" class="nav-link {{ request()->routeIs('damaged-medicines.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i>
                    <span class="nav-label">Damaged Medicines</span>
                </a>
            @endcan
            @can('stock_adjustments.view')
                <a href="{{ route('stock-adjustments.index') }}" class="nav-link {{ request()->routeIs('stock-adjustments.*') ? 'active' : '' }}">
                    <i class="bi bi-sliders"></i>
                    <span class="nav-label">Stock Adjustments</span>
                </a>
            @endcan

            @canany(['expense_categories.view','expenses.view'])
                @php $g = $navGroup('nav-expenses', 'bi-receipt-cutoff', 'Expenses', ['expenses.*','expense-categories.*']) @endphp
                <div class="nav-section">{{ $g['label'] }}</div>
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('expenses.view')
                        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                            <i class="bi bi-cash"></i>
                            <span class="nav-label">Expenses</span>
                        </a>
                    @endcan
                    @can('expense_categories.view')
                        <a href="{{ route('expense-categories.index') }}" class="nav-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                            <i class="bi bi-bookmark-dash"></i>
                            <span class="nav-label">Expense Categories</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            @canany(['cash_accounts.view','payments.view'])
                @php $g = $navGroup('nav-payment-accounts', 'bi-wallet2', 'Payments', ['cash-accounts.*','payments.*']) @endphp
                <div class="nav-section">{{ $g['label'] }}</div>
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('cash_accounts.view')
                        <a href="{{ route('cash-accounts.index') }}" class="nav-link {{ request()->routeIs('cash-accounts.*') ? 'active' : '' }}">
                            <i class="bi bi-bank"></i>
                            <span class="nav-label">Accounts</span>
                        </a>
                    @endcan
                    @can('payments.view')
                        <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                            <i class="bi bi-credit-card"></i>
                            <span class="nav-label">Payments</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            @canany(['discounts.view','price_groups.view','warranties.view','customer_groups.view','tax_rates.view'])
                @php $g = $navGroup('nav-configuration', 'bi-gear-wide-connected', 'Configuration', ['discounts.*','price-groups.*','warranties.*','customer-groups.*','tax-rates.*','update-price.*']) @endphp
                <div class="nav-section">{{ $g['label'] }}</div>
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('discounts.view')
                        <a href="{{ route('discounts.index') }}" class="nav-link {{ request()->routeIs('discounts.*') ? 'active' : '' }}">
                            <i class="bi bi-percent"></i>
                            <span class="nav-label">Discounts</span>
                        </a>
                    @endcan
                    @can('price_groups.view')
                        <a href="{{ route('price-groups.index') }}" class="nav-link {{ request()->routeIs('price-groups.*') ? 'active' : '' }}">
                            <i class="bi bi-tags"></i>
                            <span class="nav-label">Price Groups</span>
                        </a>
                    @endcan
                    @can('settings.edit')
                        <a href="{{ route('update-price.edit') }}" class="nav-link {{ request()->routeIs('update-price.*') ? 'active' : '' }}">
                            <i class="bi bi-currency-dollar"></i>
                            <span class="nav-label">Update Prices</span>
                        </a>
                    @endcan
                    @can('warranties.view')
                        <a href="{{ route('warranties.index') }}" class="nav-link {{ request()->routeIs('warranties.*') ? 'active' : '' }}">
                            <i class="bi bi-shield-check"></i>
                            <span class="nav-label">Warranties</span>
                        </a>
                    @endcan
                    @can('customer_groups.view')
                        <a href="{{ route('customer-groups.index') }}" class="nav-link {{ request()->routeIs('customer-groups.*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span class="nav-label">Customer Groups</span>
                        </a>
                    @endcan
                    @can('tax_rates.view')
                        <a href="{{ route('tax-rates.index') }}" class="nav-link {{ request()->routeIs('tax-rates.*') ? 'active' : '' }}">
                            <i class="bi bi-receipt"></i>
                            <span class="nav-label">Tax Rates</span>
                        </a>
                    @endcan
                </div>
            @endcanany

            @canany(['reports.view','cheques.view','notifications.view','settings.edit','locations.view'])
                <div class="nav-section">More</div>
            @endcanany

            @can('reports.view')
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span class="nav-label">Reports</span>
                </a>
            @endcan
            @can('cheques.view')
                <a href="{{ route('cheques.index') }}" class="nav-link {{ request()->routeIs('cheques.*') ? 'active' : '' }}">
                    <i class="bi bi-bank"></i>
                    <span class="nav-label">Cheques</span>
                </a>
            @endcan
            @can('notifications.view')
                <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i>
                    <span class="nav-label">Notifications</span>
                </a>
            @endcan

            @canany(['settings.edit','locations.view'])
                @php $g = $navGroup('nav-settings', 'bi-gear', 'Settings', ['settings.*','locations.*']) @endphp
                <a href="#{{ $g['id'] }}" class="nav-link {{ $g['active'] ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $g['active'] ? 'true' : 'false' }}">
                    <i class="bi {{ $g['icon'] }}"></i>
                    <span class="nav-label">{{ $g['label'] }}</span>
                    <i class="bi bi-chevron-down chevron"></i>
                </a>
                <div class="collapse {{ $g['active'] ? 'show' : '' }}" id="{{ $g['id'] }}">
                    @can('settings.edit')
                        <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <i class="bi bi-gear-wide-connected"></i>
                            <span class="nav-label">Business Settings</span>
                        </a>
                    @endcan
                    @can('locations.view')
                        <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt"></i>
                            <span class="nav-label">Business Locations</span>
                        </a>
                    @endcan
                </div>
            @endcanany
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link w-100">
                    <i class="bi bi-box-arrow-left"></i>
                    <span class="nav-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="app-main" id="appMain">
        <!-- Top Bar -->
        <header class="app-topbar">
            <button class="sidebar-toggle mobile-toggle" onclick="openMobileSidebar()" aria-label="Menu">
                <i class="bi bi-list"></i>
            </button>
            <button class="sidebar-toggle desktop-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                <i class="bi bi-list"></i>
            </button>

            <div class="topbar-divider"></div>

            @can('sales.create')
                <a href="{{ route('pos.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-shop me-1"></i> POS
                </a>
            @endcan

            @php $allLocations = \App\Models\Location::where('status', true)->orderBy('name')->get(); @endphp
            @if ($allLocations->count() > 1)
                <div class="dropdown">
                    <a href="#" class="text-decoration-none small dropdown-toggle" style="color:#475569" data-bs-toggle="dropdown">
                        <i class="bi bi-geo-alt me-1"></i> {{ currentLocation()?->name ?? 'Location' }}
                    </a>
                    <ul class="dropdown-menu">
                        @foreach ($allLocations as $loc)
                            <li>
                                <form method="POST" action="{{ route('locations.switch', $loc) }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item {{ currentLocationId() == $loc->id ? 'fw-bold' : '' }}">{{ $loc->name }}</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="topbar-right">
                @can('notifications.view')
                    @php $unreadNotifications = \App\Models\Notification::where('is_read', false)->latest()->limit(5)->get(); @endphp
                    <div class="dropdown">
                        <button class="topbar-icon-btn" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            @if ($unreadNotifications->count())
                                <span class="badge rounded-pill bg-danger">{{ $unreadNotifications->count() }}</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width:300px;">
                            @forelse ($unreadNotifications as $notification)
                                <li><span class="dropdown-item-text small"><strong>{{ $notification->title }}</strong><br>{{ Str::limit($notification->message, 60) }}</span></li>
                            @empty
                                <li><span class="dropdown-item-text small text-muted">No new notifications</span></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item small" href="{{ route('notifications.index') }}">View all</a></li>
                        </ul>
                    </div>
                @endcan

                <div class="topbar-divider"></div>

                <div class="dropdown">
                    <a href="#" class="topbar-user dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="topbar-user-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                        <div class="topbar-user-info d-none d-sm-block">
                            <div class="topbar-user-name">{{ auth()->user()->name }}</div>
                            <div class="topbar-user-role">{{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}</div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-muted small">{{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-left me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="app-content fade-in">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        }

        function openMobileSidebar() {
            sidebar.classList.add('mobile-open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
            document.getElementById('appMain').classList.add('sidebar-collapsed');
        }

        const observer = new MutationObserver(() => {
            document.getElementById('appMain').classList.toggle('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        });
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });

        document.querySelectorAll('.sidebar-nav .nav-link[data-bs-toggle="collapse"]').forEach(link => {
            link.addEventListener('click', () => {
                const chevron = link.querySelector('.chevron');
                if (chevron) {
                    const isExpanded = link.getAttribute('aria-expanded') === 'true';
                    chevron.style.transform = isExpanded ? '' : 'rotate(-180deg)';
                }
            });

            const isExpanded = link.getAttribute('aria-expanded') === 'true';
            const chevron = link.querySelector('.chevron');
            if (chevron && !isExpanded) {
                chevron.style.transform = 'rotate(-180deg)';
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
