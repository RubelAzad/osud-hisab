@extends('layouts.app')

@section('title', 'Expense Categories')

@section('content')
<div class="idx-header">
    <h4 class="mb-0">
        <i class="bi bi-bookmark-dash text-primary me-2"></i>Expense Categories
        <span class="idx-count">{{ $expenseCategories->total() }}</span>
    </h4>
    <div class="idx-actions">
        @can('expense_categories.create')
            <a href="{{ route('expense-categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Category</a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table idx-table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-end">Expenses</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expenseCategories as $expenseCategory)
                    <tr>
                        <td class="fw-semibold">{{ $expenseCategory->name }}</td>
                        <td class="text-end">{{ $expenseCategory->expenses_count }}</td>
                        <td class="text-end">
                            <div class="idx-actions justify-content-end">
                                @can('expense_categories.edit')
                                    <a href="{{ route('expense-categories.edit', $expenseCategory) }}" class="btn btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('expense_categories.delete')
                                    <form action="{{ route('expense-categories.destroy', $expenseCategory) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="idx-empty">
                                <i class="bi bi-bookmark-dash"></i>
                                <p>No expense categories found</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $expenseCategories->links() }}
@endsection
