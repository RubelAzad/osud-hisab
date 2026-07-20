@extends('layouts.app')

@section('title', 'Cash Accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Cash Accounts</h4>
    @can('cash_accounts.create')
        <a href="{{ route('cash-accounts.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Account</a>
    @endcan
</div>

<div class="row g-3">
    @forelse ($cashAccounts as $account)
        <div class="col-md-4">
            <a href="{{ route('cash-accounts.show', $account) }}" class="text-decoration-none">
                <div class="card">
                    <div class="card-body">
                        <div class="text-muted small">{{ $account->account_name }}</div>
                        <div class="fs-4 fw-semibold {{ $account->balance < 0 ? 'text-danger' : 'text-dark' }}">
                            {{ number_format($account->balance, 2) }}
                            @if ($account->balance < 0) <span class="fs-6">(overdrawn)</span> @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12 text-muted">No cash accounts yet.</div>
    @endforelse
</div>
@endsection
