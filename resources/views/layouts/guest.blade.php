<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }
        .auth-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0,0,0,.25);
        }
        .auth-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-radius: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }
        .form-control { border-radius: .5rem; border-color: #e2e8f0; padding: .6rem .75rem; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.15); }
        .btn-primary { border-radius: .5rem; padding: .6rem; font-weight: 500; }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 p-3">
        <div class="w-100" style="max-width: 420px;">
            <div class="text-center mb-4">
                <div class="auth-logo mx-auto">
                    <i class="bi bi-capsule"></i>
                </div>
                <h3 class="fw-bold text-white">{{ config('app.name') }}</h3>
                <p class="text-white-50">Pharmacy Management System</p>
            </div>
            <div class="card auth-card">
                <div class="card-body p-4 p-sm-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
