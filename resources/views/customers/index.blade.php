@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-person-badge text-primary me-2"></i>Customers
        <span class="idx-count">{{ $customers->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('customers.create')
            <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Customer</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th class="text-end">Balance Due</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td><a href="{{ route('customers.show', $customer) }}">{{ $customer->name }}</a></td>
                        <td>{{ $customer->phone }}</td>
                        <td class="text-muted-2">{{ $customer->email ?: '-' }}</td>
                        <td class="text-end">
                            @if($customer->balance < 0)
                                <span class="text-success fw-semibold">{{ number_format(abs($customer->balance), 2) }}</span>
                                <small class="text-muted-2">(credit)</small>
                            @else
                                {{ number_format(abs($customer->balance), 2) }}
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('customers.edit')
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('customers.delete')
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?')">
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
                                <i class="bi bi-person-badge"></i>
                                <p>No customers found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $customers->links() }}
@endsection
