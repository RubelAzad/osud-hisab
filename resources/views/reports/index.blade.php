@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<h4 class="mb-3">Reports</h4>

<div class="row g-3">
    @foreach ($reports as $slug => $meta)
        <div class="col-md-3">
            <a href="{{ route('reports.show', $slug) }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="bi bi-bar-chart-line text-primary fs-4"></i>
                        <div class="mt-2 text-dark">{{ $meta['title'] }}</div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
@endsection
