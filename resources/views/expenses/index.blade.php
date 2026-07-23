@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-cash text-primary me-2"></i>Expenses
        <span class="idx-count">{{ $expenses->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('expenses.create')
            <a href="{{ route('expenses.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Expense</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th class="text-end">Amount</th>
                    <th>Description</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date->format('d M Y') }}</td>
                        <td>{{ $expense->category->name ?? '-' }}</td>
                        <td class="text-end fw-semibold">{{ number_format($expense->amount, 2) }}</td>
                        <td class="text-muted-2">{{ Str::limit($expense->description, 50) ?: '-' }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('expenses.edit')
                                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('expenses.delete')
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this expense?')">
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
                                <i class="bi bi-cash"></i>
                                <p>No expenses found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $expenses->links() }}
@endsection
