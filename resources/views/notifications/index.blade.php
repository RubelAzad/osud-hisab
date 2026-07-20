@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Notifications</h4>
    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
        @csrf
        <button class="btn btn-outline-secondary btn-sm">Mark all as read</button>
    </form>
</div>

<div class="card">
    <ul class="list-group list-group-flush">
        @forelse ($notifications as $notification)
            <li class="list-group-item d-flex justify-content-between align-items-start {{ $notification->is_read ? '' : 'bg-light' }}">
                <div>
                    <div class="fw-semibold">
                        @if (! $notification->is_read)
                            <span class="badge bg-primary me-1">New</span>
                        @endif
                        {{ $notification->title }}
                    </div>
                    <div class="text-muted small">{{ $notification->message }}</div>
                    <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @unless ($notification->is_read)
                    <form method="POST" action="{{ route('notifications.mark-read', $notification) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-secondary">Mark read</button>
                    </form>
                @endunless
            </li>
        @empty
            <li class="list-group-item text-center text-muted py-4">No notifications.</li>
        @endforelse
    </ul>
</div>
<div class="mt-3">{{ $notifications->links() }}</div>
@endsection
