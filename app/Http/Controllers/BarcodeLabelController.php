<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarcodeLabelController extends Controller
{
    public function create(): View
    {
        $medicines = Medicine::where('status', true)->orderBy('medicine_name')->get();

        return view('medicines.barcode-labels', compact('medicines'));
    }

    public function print(Request $request): View
    {
        $labels = $request->input('labels', []);
        $medicineIds = array_keys(array_filter($labels, fn ($qty) => (int) $qty > 0));

        $medicines = Medicine::whereIn('id', $medicineIds)->get()->keyBy('id');

        $sheet = [];
        foreach ($labels as $medicineId => $qty) {
            $medicine = $medicines->get($medicineId);
            if (! $medicine || (int) $qty <= 0) {
                continue;
            }
            for ($i = 0; $i < (int) $qty; $i++) {
                $sheet[] = $medicine;
            }
        }

        return view('medicines.barcode-labels-print', ['labels' => $sheet]);
    }
}
