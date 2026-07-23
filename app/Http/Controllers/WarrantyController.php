<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarrantyRequest;
use App\Models\Warranty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarrantyController extends Controller
{
    public function index(Request $request): View
    {
        $warranties = Warranty::withCount('medicines')
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->paginate(15)
            ->withQueryString();

        return view('warranties.index', compact('warranties'));
    }

    public function create(): View
    {
        return view('warranties.create');
    }

    public function store(WarrantyRequest $request): RedirectResponse
    {
        Warranty::create($request->validated());

        return redirect()->route('warranties.index')->with('success', 'Warranty created.');
    }

    public function edit(Warranty $warranty): View
    {
        return view('warranties.edit', compact('warranty'));
    }

    public function update(WarrantyRequest $request, Warranty $warranty): RedirectResponse
    {
        $warranty->update($request->validated());

        return redirect()->route('warranties.index')->with('success', 'Warranty updated.');
    }

    public function destroy(Warranty $warranty): RedirectResponse
    {
        if ($warranty->medicines()->exists()) {
            return back()->with('error', 'Cannot delete a warranty that has medicines assigned.');
        }

        $warranty->delete();

        return redirect()->route('warranties.index')->with('success', 'Warranty deleted.');
    }
}
