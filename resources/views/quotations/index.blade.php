@extends('layouts.app')

@section('title', 'Quotations')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-file-earmark-ruled text-primary me-2"></i>Quotations
        <span class="idx-count">{{ $quotations->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('quotations.create')
            <a href="{{ route('quotations.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Quotation</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Ref</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th class="text-end">Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotations as $quotation)
                    <tr>
                        <td><a href="{{ route('quotations.show', $quotation) }}">#{{ $quotation->id }}</a></td>
                        <td>{{ $quotation->quotation_date->format('d M Y') }}</td>
                        <td>{{ $quotation->customer->name ?? 'Walk-in' }}</td>
                        <td>{{ $quotation->location->name ?? '-' }}</td>
                        <td class="text-end fw-semibold">{{ number_format($quotation->total, 2) }}</td>
                        <td>
                            <span class="badge {{ match($quotation->status) { 'converted' => 'badge-success', 'expired' => 'badge-inactive', default => 'badge-info' } }}">
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="idx-empty">
                                <i class="bi bi-file-earmark-ruled"></i>
                                <p>No quotations found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $quotations->links() }}
@endsection
