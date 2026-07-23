<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function index(): View
    {
        $shipments = Sale::with(['customer', 'location'])
            ->whereNotNull('shipping_status')
            ->latest()
            ->paginate(15);

        return view('shipments.index', compact('shipments'));
    }
}
