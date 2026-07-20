<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenericRequest;
use App\Models\Generic;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GenericController extends Controller
{
    public function index(): View
    {
        $generics = Generic::withCount('medicines')->latest()->paginate(15);

        return view('generics.index', compact('generics'));
    }

    public function create(): View
    {
        return view('generics.create');
    }

    public function store(GenericRequest $request): RedirectResponse
    {
        Generic::create($request->validated());

        return redirect()->route('generics.index')->with('success', 'Generic created.');
    }

    public function edit(Generic $generic): View
    {
        return view('generics.edit', compact('generic'));
    }

    public function update(GenericRequest $request, Generic $generic): RedirectResponse
    {
        $generic->update($request->validated());

        return redirect()->route('generics.index')->with('success', 'Generic updated.');
    }

    public function destroy(Generic $generic): RedirectResponse
    {
        if ($generic->medicines()->exists()) {
            return back()->with('error', 'Cannot delete a generic that has medicines assigned.');
        }

        $generic->delete();

        return redirect()->route('generics.index')->with('success', 'Generic deleted.');
    }
}
