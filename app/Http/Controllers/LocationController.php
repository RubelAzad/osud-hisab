<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::withCount('medicineBatches')->orderBy('name')->get();

        return view('locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('locations.create');
    }

    public function store(LocationRequest $request): RedirectResponse
    {
        Location::create($request->validated());

        return redirect()->route('locations.index')->with('success', 'Location created.');
    }

    public function edit(Location $location): View
    {
        return view('locations.edit', compact('location'));
    }

    public function update(LocationRequest $request, Location $location): RedirectResponse
    {
        $location->update($request->validated());

        return redirect()->route('locations.index')->with('success', 'Location updated.');
    }
}
