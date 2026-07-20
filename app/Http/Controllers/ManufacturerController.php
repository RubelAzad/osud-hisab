<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManufacturerRequest;
use App\Models\Manufacturer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    public function index(): View
    {
        $manufacturers = Manufacturer::withCount('medicines')->latest()->paginate(15);

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
