<?php

namespace App\Http\Controllers;

use App\Http\Requests\PharmacySettingsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('settings.edit', ['pharmacy' => currentPharmacy()]);
    }

    public function update(PharmacySettingsRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $pharmacy = currentPharmacy();

        if ($request->hasFile('logo')) {
            if ($pharmacy->logo) {
                Storage::disk('public')->delete($pharmacy->logo);
            }
            $data['logo'] = $request->file('logo')->store('pharmacies', 'public');
        }

        $pharmacy->update($data);

        return back()->with('success', 'Settings updated.');
    }
}
