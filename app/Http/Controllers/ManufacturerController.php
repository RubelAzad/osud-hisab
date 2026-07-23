<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManufacturerRequest;
use App\Models\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    public function index(Request $request): View
    {
        $manufacturers = Manufacturer::withCount('medicines')
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status') === 'active'))
            ->paginate(15)
            ->withQueryString();

        return view('manufacturers.index', compact('manufacturers'));
    }

    public function create(): View
    {
        return view('manufacturers.create');
    }

    public function store(ManufacturerRequest $request): RedirectResponse
    {
        Manufacturer::create($request->validated());

        return redirect()->route('manufacturers.index')->with('success', 'Manufacturer created.');
    }

    public function edit(Manufacturer $manufacturer): View
    {
        return view('manufacturers.edit', compact('manufacturer'));
    }

    public function update(ManufacturerRequest $request, Manufacturer $manufacturer): RedirectResponse
    {
        $manufacturer->update($request->validated());

        return redirect()->route('manufacturers.index')->with('success', 'Manufacturer updated.');
    }

    public function destroy(Manufacturer $manufacturer): RedirectResponse
    {
        if ($manufacturer->medicines()->exists()) {
            return back()->with('error', 'Cannot delete a manufacturer that has medicines assigned.');
        }

        $manufacturer->delete();

        return redirect()->route('manufacturers.index')->with('success', 'Manufacturer deleted.');
    }
}
