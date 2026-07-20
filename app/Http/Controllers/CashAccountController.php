<?php

namespace App\Http\Controllers;

use App\Http\Requests\CashAccountRequest;
use App\Models\CashAccount;
use Illuminate\Http\RedirectResponse;
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

    public function show(CashAccount $cashAccount): View
    {
        $transactions = $cashAccount->transactions()->latest('transaction_date')->latest('id')->paginate(20);

        return view('cash-accounts.show', compact('cashAccount', 'transactions'));
    }
}
