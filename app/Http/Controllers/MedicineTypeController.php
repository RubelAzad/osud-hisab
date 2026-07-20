<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicineTypeRequest;
use App\Models\MedicineType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MedicineTypeController extends Controller
{
    public function index(): View
    {
        $medicineTypes = MedicineType::withCount('medicines')->latest()->paginate(15);

        return view('medicine-types.index', compact('medicineTypes'));
    }

    public function create(): View
    {
        return view('medicine-types.create');
    }

    public function store(MedicineTypeRequest $request): RedirectResponse
    {
        MedicineType::create($request->validated());

        return redirect()->route('medicine-types.index')->with('success', 'Medicine type created.');
    }

    public function edit(MedicineType $medicine_type): View
    {
        return view('medicine-types.edit', ['medicineType' => $medicine_type]);
    }

    public function update(MedicineTypeRequest $request, MedicineType $medicine_type): RedirectResponse
    {
        $medicine_type->update($request->validated());

        return redirect()->route('medicine-types.index')->with('success', 'Medicine type updated.');
    }

    public function destroy(MedicineType $medicine_type): RedirectResponse
    {
        if ($medicine_type->medicines()->exists()) {
            return back()->with('error', 'Cannot delete a medicine type that has medicines assigned.');
        }

        $medicine_type->delete();

        return redirect()->route('medicine-types.index')->with('success', 'Medicine type deleted.');
    }
}
