@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-bar-chart-line text-primary me-2"></i>Reports
    </h4>
</div>

<div class="row g-3">
    @foreach ($reports as $slug => $meta)
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('reports.show', $slug) }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:.65rem;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-bar-chart-line text-primary" style="font-size:1.3rem;"></i>
                        </div>
                        <div class="text-dark fw-semibold">{{ $meta['title'] }}</div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
    @can('activity_logs.view')
        <div class="col-md-3 col-sm-6">
            <a href="{{ route('activity-logs.index') }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;border-radius:.65rem;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-clock-history text-success" style="font-size:1.3rem;"></i>
                        </div>
                        <div class="text-dark fw-semibold">Activity Log</div>
                    </div>
                </div>
            </a>
        </div>
    @endcan
</div>
@endsection
