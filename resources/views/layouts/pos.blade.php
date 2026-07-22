<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        html, body { height: 100%; background-color: #f4f6f9; overflow: hidden; }
        .pos-topbar { height: 56px; }
        .pos-body { height: calc(100vh - 56px); }
        .pos-products { overflow-y: auto; }
        .pos-cart { overflow-y: auto; background: #fff; }
        .product-tile { cursor: pointer; user-select: none; transition: transform .05s; }
        .product-tile:active { transform: scale(0.97); }
        .product-tile:hover { border-color: #0d6efd !important; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="pos-topbar navbar navbar-dark bg-dark px-3 d-flex align-items-center">
        <a href="{{ route('dashboard') }}" class="navbar-brand mb-0">
            <i class="bi bi-arrow-left-circle me-1"></i> {{ currentPharmacy()?->name ?? config('app.name') }}
        </a>
        <span class="text-light small ms-3"><i class="bi bi-geo-alt me-1"></i>{{ currentLocation()?->name ?? 'Main Branch' }}</span>
        <span class="text-light small ms-auto me-3">{{ auth()->user()->name }}</span>
    </nav>

    <div class="pos-body">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
