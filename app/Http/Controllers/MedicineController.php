<?php

namespace App\Http\Controllers;

use App\Exports\MedicinesExport;
use App\Http\Requests\MedicineRequest;
use App\Imports\MedicinesImport;
use App\Models\Category;
use App\Models\Generic;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\MedicineType;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class MedicineController extends Controller
{
    public function index(Request $request): View
    {
        $medicines = Medicine::with(['category', 'manufacturer', 'unit'])
            ->withSum('batches as total_stock', 'remaining_qty')
            ->when($request->filled('q'), fn ($q) => $q->where('medicine_name', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('medicines.index', compact('medicines'));
    }

    public function create(): View
    {
        return view('medicines.create', $this->formData());
    }

    public function store(MedicineRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('medicines', 'public');
        }

        if (empty($data['barcode'])) {
            $data['barcode'] = 'MED'.str_pad((string) (Medicine::max('id') + 1), 8, '0', STR_PAD_LEFT);
        }

        Medicine::create($data);

        return redirect()->route('medicines.index')->with('success', 'Medicine created.');
    }

    public function show(Medicine $medicine): View
    {
        $medicine->load(['category', 'manufacturer', 'generic', 'medicineType', 'unit']);
        $batches = $medicine->batches()->with('location')->latest()->paginate(10);

        return view('medicines.show', compact('medicine', 'batches'));
    }

    public function edit(Medicine $medicine): View
    {
        return view('medicines.edit', array_merge(['medicine' => $medicine], $this->formData()));
    }

    public function update(MedicineRequest $request, Medicine $medicine): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($medicine->image) {
                Storage::disk('public')->delete($medicine->image);
            }
            $data['image'] = $request->file('image')->store('medicines', 'public');
        }

        $medicine->update($data);

        return redirect()->route('medicines.index')->with('success', 'Medicine updated.');
    }

    public function destroy(Medicine $medicine): RedirectResponse
    {
        if ($medicine->batches()->exists()) {
            return back()->with('error', 'Cannot delete a medicine that has purchase/stock history.');
        }

        $medicine->delete();

        return redirect()->route('medicines.index')->with('success', 'Medicine deleted.');
    }

    public function export()
    {
        $filename = 'medicines-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(new MedicinesExport, $filename);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx,xls,csv']]);

        $import = new MedicinesImport;
        Excel::import($import, $request->file('file'));

        return redirect()->route('medicines.index')
            ->with('success', "Imported {$import->imported} medicines ({$import->skipped} skipped — either a duplicate barcode or an unrecognized category/manufacturer/generic/unit/type name).");
    }

    private function formData(): array
    {
        return [
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'manufacturers' => Manufacturer::where('status', true)->orderBy('name')->get(),
            'generics' => Generic::orderBy('name')->get(),
            'medicineTypes' => MedicineType::orderBy('name')->get(),
            'units' => Unit::orderBy('name')->get(),
        ];
    }
}
