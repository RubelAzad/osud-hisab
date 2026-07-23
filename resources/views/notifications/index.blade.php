@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-bell text-primary me-2"></i>Notifications
    </h4>
    <div class="idx-actions">
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button class="btn btn-outline-secondary"><i class="bi bi-check-all me-1"></i>Mark all as read</button>
        </form>
    </div>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2">
            <select name="is_read" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>Unread</option>
                <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>Read</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="type" class="form-control form-control-sm" placeholder="Filter by type..." value="{{ request('type') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="list-group list-group-flush">
        @forelse ($notifications as $notification)
            <div class="list-group-item d-flex justify-content-between align-items-start {{ $notification->is_read ? '' : 'bg-primary-subtle bg-opacity-10' }}">
                <div class="flex-grow-1">
                    <div class="fw-semibold small">
                        @if (! $notification->is_read)
                            <span class="badge badge-info me-1">New</span>
                        @endif
                        {{ $notification->title }}
                    </div>
                    <div class="text-muted-2 small mt-1">{{ $notification->message }}</div>
                    <div class="text-muted-2 small mt-1"><i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @unless ($notification->is_read)
                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-secondary" title="Mark as read"><i class="bi bi-check-lg"></i></button>
                    </form>
                @endunless
            </div>
        @empty
            <div class="list-group-item">
                <div class="idx-empty">
                    <i class="bi bi-bell"></i>
                    <p>No notifications</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{ $notifications->links() }}
@endsection
