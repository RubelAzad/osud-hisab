@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-clock-history text-primary me-2"></i>Activity Log
        <span class="idx-count">{{ $logs->total() }}</span>
    </h4>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3 col-sm-6">
            <select name="user_id" class="form-select">
                <option value="">All Users</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-sm-6">
            <input type="text" name="table_name" class="form-control" placeholder="Table name..." value="{{ request('table_name') }}">
        </div>
        <div class="col-md-3 col-sm-6">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
        </div>
        @if(request('user_id') || request('table_name') || request('date'))
            <div class="col-auto">
                <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg me-1"></i>Clear</a>
            </div>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date / Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Table</th>
                    <th>Record ID</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>
                            <span class="badge {{ match($log->action) { 'created' => 'badge-success', 'deleted' => 'badge-danger', default => 'badge-info' } }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>{{ $log->table_name }}</td>
                        <td>{{ $log->record_id }}</td>
                        <td class="text-muted-2">{{ $log->ip }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-clock-history"></i>
                                <p>No activity recorded yet</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $logs->links() }}
@endsection
