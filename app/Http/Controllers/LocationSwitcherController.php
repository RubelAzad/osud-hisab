<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\RedirectResponse;

class LocationSwitcherController extends Controller
{
    public function switch(Location $location): RedirectResponse
    {
        abort_unless($location->pharmacy_id === currentPharmacyId(), 404);

        session(['current_location_id' => $location->id]);

        return back()->with('success', "Switched to {$location->name}.");
    }
}
