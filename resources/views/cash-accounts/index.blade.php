@extends('layouts.app')

@section('title', 'Cash Accounts')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-bank text-primary me-2"></i>Cash Accounts
        <span class="idx-count">{{ $cashAccounts->count() }}</span>
    </h4>
    <div class="idx-actions">
        @can('cash_accounts.create')
            <a href="{{ route('cash-accounts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Account</a>
        @endcan
    </div>
</div>

<div class="row g-3">
    @forelse ($cashAccounts as $account)
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('cash-accounts.show', $account) }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <div style="width:44px;height:44px;border-radius:.65rem;background:{{ $account->balance < 0 ? '#fee2e2' : '#dcfce7' }};display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-wallet2" style="font-size:1.2rem;color:{{ $account->balance < 0 ? '#dc2626' : '#16a34a' }}"></i>
                                </div>
                            </div>
                            <div>
                                <div class="text-muted-2 small">{{ $account->account_name }}</div>
                                <div class="fw-bold {{ $account->balance < 0 ? 'text-danger' : 'text-dark' }}" style="font-size:1.15rem;">
                                    {{ number_format($account->balance, 2) }}
                                    @if ($account->balance < 0) <small class="text-muted-2 fw-normal">(overdrawn)</small> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-12">
            <div class="idx-empty">
                <i class="bi bi-bank"></i>
                <p>No cash accounts found</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
