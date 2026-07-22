<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #1e2a38;
        }
        .sidebar .nav-link {
            color: #c7d0d9;
            padding: .55rem 1rem;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            color: #fff;
            background-color: #2c3e50;
        }
        .sidebar .nav-header {
            color: #8a97a5;
            font-size: .72rem;
            text-transform: uppercase;
            padding: .75rem 1rem .25rem;
            letter-spacing: .04em;
        }
        .main-content { flex: 1; min-width: 0; }
        .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar d-flex flex-column flex-shrink-0">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center px-3 py-3 text-white text-decoration-none border-bottom border-secondary-subtle">
                <i class="bi bi-capsule fs-4 me-2"></i>
                <span class="fs-5 fw-semibold">{{ currentPharmacy()?->name ?? config('app.name') }}</span>
            </a>
            <div class="nav flex-column flex-nowrap overflow-auto py-2">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>

                @canany(['categories.view','manufacturers.view','generics.view','medicine_types.view','units.view','medicines.view'])
                    <div class="nav-header">Inventory</div>
                @endcanany
                @can('medicines.view')
                    <a href="{{ route('medicines.index') }}" class="nav-link {{ request()->routeIs('medicines.*') ? 'active' : '' }}"><i class="bi bi-capsule-pill me-2"></i> Medicines</a>
                @endcan
                @can('categories.view')
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"><i class="bi bi-tags me-2"></i> Categories</a>
                @endcan
                @can('manufacturers.view')
                    <a href="{{ route('manufacturers.index') }}" class="nav-link {{ request()->routeIs('manufacturers.*') ? 'active' : '' }}"><i class="bi bi-building me-2"></i> Manufacturers</a>
                @endcan
                @can('generics.view')
                    <a href="{{ route('generics.index') }}" class="nav-link {{ request()->routeIs('generics.*') ? 'active' : '' }}"><i class="bi bi-eyedropper me-2"></i> Generics</a>
                @endcan
                @can('medicine_types.view')
                    <a href="{{ route('medicine-types.index') }}" class="nav-link {{ request()->routeIs('medicine-types.*') ? 'active' : '' }}"><i class="bi bi-list-check me-2"></i> Medicine Types</a>
                @endcan
                @can('units.view')
                    <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}"><i class="bi bi-rulers me-2"></i> Units</a>
                @endcan
                @can('damaged_medicines.view')
                    <a href="{{ route('damaged-medicines.index') }}" class="nav-link {{ request()->routeIs('damaged-medicines.*') ? 'active' : '' }}"><i class="bi bi-exclamation-triangle me-2"></i> Damaged Medicines</a>
                @endcan

                @canany(['purchases.view','sales.view','purchase_returns.view','sale_returns.view'])
                    <div class="nav-header">Transactions</div>
                @endcanany
                @can('purchases.view')
                    <a href="{{ route('purchases.index') }}" class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}"><i class="bi bi-box-arrow-in-down me-2"></i> Purchases</a>
                @endcan
                @can('sales.view')
                    <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"><i class="bi bi-cart-check me-2"></i> Sales</a>
                @endcan
                @can('purchase_returns.view')
                    <a href="{{ route('purchase-returns.index') }}" class="nav-link {{ request()->routeIs('purchase-returns.*') ? 'active' : '' }}"><i class="bi bi-arrow-return-left me-2"></i> Purchase Returns</a>
                @endcan
                @can('sale_returns.view')
                    <a href="{{ route('sale-returns.index') }}" class="nav-link {{ request()->routeIs('sale-returns.*') ? 'active' : '' }}"><i class="bi bi-arrow-return-left me-2"></i> Sale Returns</a>
                @endcan
                @can('stock_transfers.view')
                    <a href="{{ route('stock-transfers.index') }}" class="nav-link {{ request()->routeIs('stock-transfers.*') ? 'active' : '' }}"><i class="bi bi-arrow-left-right me-2"></i> Stock Transfers</a>
                @endcan

                @canany(['suppliers.view','customers.view'])
                    <div class="nav-header">Partners</div>
                @endcanany
                @can('suppliers.view')
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"><i class="bi bi-truck me-2"></i> Suppliers</a>
                @endcan
                @can('customers.view')
                    <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"><i class="bi bi-people me-2"></i> Customers</a>
                @endcan

                @canany(['payments.view','expense_categories.view','expenses.view','cash_accounts.view','reports.view'])
                    <div class="nav-header">Finance</div>
                @endcanany
                @can('payments.view')
                    <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}"><i class="bi bi-cash-coin me-2"></i> Payments</a>
                @endcan
                @can('expense_categories.view')
                    <a href="{{ route('expense-categories.index') }}" class="nav-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}"><i class="bi bi-tags me-2"></i> Expense Categories</a>
                @endcan
                @can('expenses.view')
                    <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}"><i class="bi bi-receipt me-2"></i> Expenses</a>
                @endcan
                @can('cash_accounts.view')
                    <a href="{{ route('cash-accounts.index') }}" class="nav-link {{ request()->routeIs('cash-accounts.*') ? 'active' : '' }}"><i class="bi bi-wallet2 me-2"></i> Cash Accounts</a>
                @endcan
                @can('reports.view')
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line me-2"></i> Reports</a>
                @endcan

                @canany(['users.view','roles.view','settings.edit'])
                    <div class="nav-header">Administration</div>
                @endcanany
                @can('users.view')
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="bi bi-person-badge me-2"></i> Users</a>
                @endcan
                @can('roles.view')
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"><i class="bi bi-shield-lock me-2"></i> Roles</a>
                @endcan
                @can('locations.view')
                    <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}"><i class="bi bi-geo-alt me-2"></i> Locations</a>
                @endcan
                @can('settings.edit')
                    <a href="{{ route('settings.edit') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"><i class="bi bi-gear me-2"></i> Settings</a>
                @endcan
            </div>
        </nav>

        <div class="main-content">
            <nav class="navbar navbar-expand navbar-light bg-white border-bottom px-3">
                @can('sales.create')
                    <a href="{{ route('pos.index') }}" class="btn btn-sm btn-primary me-3">
                        <i class="bi bi-shop me-1"></i> Point of Sale
                    </a>
                @endcan

                @php $allLocations = \App\Models\Location::where('status', true)->orderBy('name')->get(); @endphp
                @if ($allLocations->count() > 1)
                    <div class="dropdown me-3">
                        <a href="#" class="text-dark dropdown-toggle small" data-bs-toggle="dropdown">
                            <i class="bi bi-geo-alt me-1"></i> {{ currentLocation()?->name ?? 'Select Location' }}
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

                @can('notifications.view')
                    @php $unreadNotifications = \App\Models\Notification::where('is_read', false)->latest()->limit(5)->get(); @endphp
                    <div class="dropdown ms-auto me-3">
                        <a href="#" class="position-relative text-dark dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-5"></i>
                            @if ($unreadNotifications->count())
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">
                                    {{ $unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
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
                <div class="dropdown {{ auth()->user()->can('notifications.view') ? '' : 'ms-auto' }}">
                    <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5 me-2"></i>
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-muted small">{{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
