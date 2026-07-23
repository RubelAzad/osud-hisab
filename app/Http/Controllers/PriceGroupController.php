<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceGroupRequest;
use App\Models\Medicine;
use App\Models\PriceGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PriceGroupController extends Controller
{
    public function index(Request $request): View
    {
        $priceGroups = PriceGroup::withCount('medicines')
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status') === 'active'))
            ->paginate(15)
            ->withQueryString();

        return view('price-groups.index', compact('priceGroups'));
    }

    public function create(): View
    {
        return view('price-groups.create');
    }

    public function store(PriceGroupRequest $request): RedirectResponse
    {
        PriceGroup::create($request->validated());

        return redirect()->route('price-groups.index')->with('success', 'Price group created.');
    }

    public function edit(PriceGroup $priceGroup): View
    {
        return view('price-groups.edit', compact('priceGroup'));
    }

    public function update(PriceGroupRequest $request, PriceGroup $priceGroup): RedirectResponse
    {
        $priceGroup->update($request->validated());

        return redirect()->route('price-groups.index')->with('success', 'Price group updated.');
    }

    public function destroy(PriceGroup $priceGroup): RedirectResponse
    {
        if ($priceGroup->medicines()->exists()) {
            return back()->with('error', 'Cannot delete a price group that has medicine prices assigned.');
        }

        $priceGroup->delete();

        return redirect()->route('price-groups.index')->with('success', 'Price group deleted.');
    }

    public function editPrices(PriceGroup $priceGroup): View
    {
        $medicines = Medicine::where('status', true)
            ->with(['priceGroups' => fn ($q) => $q->where('price_groups.id', $priceGroup->id)])
            ->orderBy('medicine_name')
            ->get();

        return view('price-groups.prices', compact('priceGroup', 'medicines'));
    }

    public function updatePrices(Request $request, PriceGroup $priceGroup): RedirectResponse
    {
        $prices = $request->input('prices', []);

        foreach ($prices as $medicineId => $price) {
            if ($price === null || $price === '') {
                $priceGroup->medicines()->detach($medicineId);

                continue;
            }

            $priceGroup->medicines()->syncWithoutDetaching([$medicineId => ['price' => (float) $price]]);
        }

        return redirect()->route('price-groups.index')->with('success', 'Prices updated.');
    }
}
