<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Services\ExpenseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(private readonly ExpenseService $expenseService) {}

    public function index(Request $request): View
    {
        $expenses = Expense::with('category')
            ->latest('expense_date')->latest('id')
            ->when($request->filled('q'), fn ($q) => $q->where('description', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category_id'), fn ($q) => $q->where('expense_category_id', $request->integer('category_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('expense_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('expense_date', '<=', $request->input('to')))
            ->paginate(15)
            ->withQueryString();

        return view('expenses.index', compact('expenses'));
    }

    public function create(): View
    {
        return view('expenses.create', ['categories' => ExpenseCategory::orderBy('name')->get()]);
    }

    public function store(ExpenseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $this->expenseService->create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded.');
    }

    public function edit(Expense $expense): View
    {
        return view('expenses.edit', ['expense' => $expense, 'categories' => ExpenseCategory::orderBy('name')->get()]);
    }

    public function update(ExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $this->expenseService->update($expense, $request->validated());

        return redirect()->route('expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $this->expenseService->delete($expense);

        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}
