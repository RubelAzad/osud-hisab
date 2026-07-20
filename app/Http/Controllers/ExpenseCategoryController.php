<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseCategoryRequest;
use App\Models\ExpenseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpenseCategoryController extends Controller
{
    public function index(): View
    {
        $expenseCategories = ExpenseCategory::withCount('expenses')->orderBy('name')->paginate(15);

        return view('expense-categories.index', compact('expenseCategories'));
    }

    public function create(): View
    {
        return view('expense-categories.create');
    }

    public function store(ExpenseCategoryRequest $request): RedirectResponse
    {
        ExpenseCategory::create($request->validated());

        return redirect()->route('expense-categories.index')->with('success', 'Expense category created.');
    }

    public function edit(ExpenseCategory $expense_category): View
    {
        return view('expense-categories.edit', ['expenseCategory' => $expense_category]);
    }

    public function update(ExpenseCategoryRequest $request, ExpenseCategory $expense_category): RedirectResponse
    {
        $expense_category->update($request->validated());

        return redirect()->route('expense-categories.index')->with('success', 'Expense category updated.');
    }

    public function destroy(ExpenseCategory $expense_category): RedirectResponse
    {
        if ($expense_category->expenses()->exists()) {
            return back()->with('error', 'Cannot delete a category that has expenses.');
        }

        $expense_category->delete();

        return redirect()->route('expense-categories.index')->with('success', 'Expense category deleted.');
    }
}
