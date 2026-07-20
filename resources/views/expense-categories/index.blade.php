@extends('layouts.app')

@section('title', 'Expense Categories')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Expense Categories</h4>
    @can('expense_categories.create')
        <a href="{{ route('expense-categories.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Category</a>
    @endcan
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Name</th><th>Expenses</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($expenseCategories as $expenseCategory)
                    <tr>
                        <td>{{ $expenseCategory->name }}</td>
                        <td>{{ $expenseCategory->expenses_count }}</td>
                        <td class="text-end">
                            @can('expense_categories.edit')
                                <a href="{{ route('expense-categories.edit', $expenseCategory) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            @endcan
                            @can('expense_categories.delete')
                                <form action="{{ route('expense-categories.destroy', $expenseCategory) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No expense categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $expenseCategories->links() }}</div>
@endsection
