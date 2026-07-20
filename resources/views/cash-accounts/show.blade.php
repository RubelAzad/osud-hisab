@extends('layouts.app')

@section('title', $cashAccount->account_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $cashAccount->account_name }}</h4>
    <div class="fs-5 fw-semibold {{ $cashAccount->balance < 0 ? 'text-danger' : '' }}">
        Balance: {{ number_format($cashAccount->balance, 2) }}{{ $cashAccount->balance < 0 ? ' (overdrawn)' : '' }}
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">Transaction History</div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>Date</th><th>Type</th><th>Reference</th><th>Credit</th><th>Debit</th></tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                        <td>{{ $transaction->type }}</td>
                        <td class="text-muted">{{ $transaction->reference }} @if($transaction->reference_id) #{{ $transaction->reference_id }} @endif</td>
                        <td class="text-success">{{ $transaction->credit > 0 ? number_format($transaction->credit, 2) : '-' }}</td>
                        <td class="text-danger">{{ $transaction->debit > 0 ? number_format($transaction->debit, 2) : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No transactions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $transactions->links() }}</div>
@endsection
