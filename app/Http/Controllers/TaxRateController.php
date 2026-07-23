<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxRateRequest;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TaxRateController extends Controller
{
    public function index(): View
    {
        $taxRates = TaxRate::withCount('medicines')->latest()->paginate(15);

        return view('tax-rates.index', compact('taxRates'));
    }

    public function create(): View
    {
        return view('tax-rates.create');
    }

    public function store(TaxRateRequest $request): RedirectResponse
    {
        TaxRate::create($request->validated());

        return redirect()->route('tax-rates.index')->with('success', 'Tax rate created.');
    }

    public function edit(TaxRate $taxRate): View
    {
        return view('tax-rates.edit', compact('taxRate'));
    }

    public function update(TaxRateRequest $request, TaxRate $taxRate): RedirectResponse
    {
        $taxRate->update($request->validated());

        return redirect()->route('tax-rates.index')->with('success', 'Tax rate updated.');
    }

    public function destroy(TaxRate $taxRate): RedirectResponse
    {
        if ($taxRate->medicines()->exists()) {
            return back()->with('error', 'Cannot delete a tax rate that has medicines assigned.');
        }

        $taxRate->delete();

        return redirect()->route('tax-rates.index')->with('success', 'Tax rate deleted.');
    }
}
