@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $title }}</h4>
    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm">All Reports</a>
</div>

@if ($filterType !== 'none')
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                @if ($filterType === 'date')
                    <div class="col-auto">
                        <label class="form-label small mb-0">Date</label>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ $filters['date'] ?? now()->format('Y-m-d') }}">
                    </div>
                @elseif ($filterType === 'month')
                    <div class="col-auto">
                        <label class="form-label small mb-0">Year</label>
                        <input type="number" name="year" class="form-control form-control-sm" value="{{ $filters['year'] ?? now()->year }}">
                    </div>
                    <div class="col-auto">
                        <label class="form-label small mb-0">Month</label>
                        <input type="number" name="month" min="1" max="12" class="form-control form-control-sm" value="{{ $filters['month'] ?? now()->month }}">
                    </div>
                @elseif ($filterType === 'range')
                    <div class="col-auto">
                        <label class="form-label small mb-0">From</label>
                        <input type="date" name="from" class="form-control form-control-sm" value="{{ $filters['from'] ?? now()->startOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col-auto">
                        <label class="form-label small mb-0">To</label>
                        <input type="date" name="to" class="form-control form-control-sm" value="{{ $filters['to'] ?? now()->format('Y-m-d') }}">
                    </div>
                @elseif ($filterType === 'days')
                    <div class="col-auto">
                        <label class="form-label small mb-0">Days</label>
                        <input type="number" name="days" class="form-control form-control-sm" value="{{ $filters['days'] ?? 30 }}">
                    </div>
                @endif
                <div class="col-auto">
                    <button class="btn btn-outline-secondary btn-sm">Apply</button>
                </div>
            </form>
        </div>
    </div>
@endif

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    @foreach ($data['columns'] as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($data['rows'] as $row)
                    <tr>
                        @foreach ($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ count($data['columns']) }}" class="text-center text-muted py-4">No data for this period.</td></tr>
                @endforelse
            </tbody>
            @if (isset($data['total_label']))
                <tfoot>
                    <tr class="fw-semibold">
                        <td colspan="{{ count($data['columns']) - 1 }}" class="text-end">{{ $data['total_label'] }}</td>
                        <td>{{ $data['total_value'] }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
