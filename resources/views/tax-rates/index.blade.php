@extends('layouts.app')

@section('title', 'Tax Rates')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-percent text-primary me-2"></i>Tax Rates
        <span class="idx-count">{{ $taxRates->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('tax_rates.create')
            <a href="{{ route('tax-rates.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Tax Rate</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-end">Rate</th>
                    <th class="text-end">Medicines</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($taxRates as $taxRate)
                    <tr>
                        <td class="fw-semibold">{{ $taxRate->name }}</td>
                        <td class="text-end fw-semibold">{{ number_format($taxRate->rate, 2) }}%</td>
                        <td class="text-end">{{ $taxRate->medicines_count }}</td>
                        <td>
                            <span class="badge {{ $taxRate->status ? 'badge-active' : 'badge-inactive' }}">
                                {{ $taxRate->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('tax_rates.edit')
                                    <a href="{{ route('tax-rates.edit', $taxRate) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('tax_rates.delete')
                                    <form action="{{ route('tax-rates.destroy', $taxRate) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tax rate?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="idx-empty">
                                <i class="bi bi-percent"></i>
                                <p>No tax rates found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $taxRates->links() }}
@endsection
