<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UpdatePriceController extends Controller
{
    public function edit(Request $request): View
    {
        $medicines = Medicine::where('status', true)
            ->when($request->filled('q'), fn ($q) => $q->where('medicine_name', 'like', '%'.$request->string('q').'%'))
            ->orderBy('medicine_name')
            ->paginate(30)
            ->withQueryString();

        return view('update-price.edit', compact('medicines'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'prices' => ['required', 'array'],
            'prices.*.purchase_price' => ['required', 'numeric', 'min:0'],
            'prices.*.sale_price' => ['required', 'numeric', 'min:0'],
        ]);

        foreach ($data['prices'] as $medicineId => $prices) {
            $medicine = Medicine::find($medicineId);

            if (! $medicine) {
                continue;
            }

            $medicine->update($prices);
            ActivityLog::record('updated', $medicine);
        }

        return redirect()->route('update-price.edit')->with('success', 'Prices updated.');
    }
}
