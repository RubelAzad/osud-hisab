<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashAccountRequest;
use App\Models\CashAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashAccountController extends Controller
{
    public function index(): View
    {
        $cashAccounts = CashAccount::latest()->get();

        return view('cash-accounts.index', compact('cashAccounts'));
    }

    public function create(): View
    {
        return view('cash-accounts.create');
    }

    public function store(CashAccountRequest $request): RedirectResponse
    {
        CashAccount::create(array_merge($request->validated(), ['balance' => 0]));

        return redirect()->route('cash-accounts.index')->with('success', 'Account created.');
    }

    public function show(CashAccount $cashAccount, Request $request): View
    {
        $transactions = $cashAccount->transactions()
            ->latest('transaction_date')->latest('id')
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->when($request->filled('from'), fn ($q) => $q->where('transaction_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('transaction_date', '<=', $request->input('to')))
            ->paginate(20)
            ->withQueryString();

        return view('cash-accounts.show', compact('cashAccount', 'transactions'));
    }
}
