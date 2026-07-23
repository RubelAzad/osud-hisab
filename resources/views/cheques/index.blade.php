@extends('layouts.app')

@section('title', 'Cheques')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-bank text-primary me-2"></i>Cheques
        <span class="idx-count">{{ $cheques->total() }}</span>
    </h4>
</div>

<div class="idx-filters">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3 col-sm-6">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="cleared" {{ request('status') === 'cleared' ? 'selected' : '' }}>Cleared</option>
                <option value="bounced" {{ request('status') === 'bounced' ? 'selected' : '' }}>Bounced</option>
            </select>
        </div>
        @if(request('status'))
            <div class="col-auto">
                <a href="{{ route('cheques.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg me-1"></i>Clear</a>
            </div>
        @endif
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Cheque No</th>
                    <th>Bank</th>
                    <th>Party</th>
                    <th class="text-end">Amount</th>
                    <th>Cheque Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cheques as $cheque)
                    <tr>
                        <td class="fw-semibold">{{ $cheque->cheque_no }}</td>
                        <td>{{ $cheque->bank_name }}</td>
                        <td>
                            @if ($cheque->payment->customer)
                                <a href="{{ route('customers.show', $cheque->payment->customer) }}">{{ $cheque->payment->customer->name }}</a>
                            @elseif ($cheque->payment->supplier)
                                <a href="{{ route('suppliers.show', $cheque->payment->supplier) }}">{{ $cheque->payment->supplier->name }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end fw-semibold">{{ number_format($cheque->payment->amount, 2) }}</td>
                        <td>{{ $cheque->cheque_date->format('d M Y') }}</td>
                        <td>{{ $cheque->due_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ match($cheque->status) { 'cleared' => 'badge-success', 'bounced' => 'badge-danger', default => 'badge-pending' } }}">
                                {{ ucfirst($cheque->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            @can('cheques.edit')
                                @if ($cheque->status === 'pending')
                                    <div class="idx-actions justify-content-end">
                                        <form method="POST" action="{{ route('cheques.update-status', $cheque) }}" class="d-flex gap-1">
                                            @csrf @method('PATCH')
                                            <button type="submit" name="status" value="cleared" class="btn btn-outline-success btn-sm">Clear</button>
                                            <button type="submit" name="status" value="bounced" class="btn btn-outline-danger btn-sm">Bounce</button>
                                        </form>
                                    </div>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="idx-empty">
                                <i class="bi bi-bank"></i>
                                <p>No cheques found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $cheques->links() }}
@endsection
