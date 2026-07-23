<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenericRequest;
use App\Models\Generic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GenericController extends Controller
{
    public function index(Request $request): View
    {
        $generics = Generic::latest()
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->paginate(15)
            ->withQueryString();

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
