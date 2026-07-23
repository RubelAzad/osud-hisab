<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxRateRequest;
use App\Models\TaxRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaxRateController extends Controller
{
    public function index(Request $request): View
    {
        $taxRates = TaxRate::withCount('medicines')
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status') === 'active'))
            ->paginate(15)
            ->withQueryString();

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
