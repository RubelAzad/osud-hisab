<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function index(Request $request): View
    {
        $shipments = Sale::with(['customer', 'location'])
            ->whereNotNull('shipping_status')
            ->latest()
            ->when($request->filled('shipping_status'), fn ($q) => $q->where('shipping_status', $request->input('shipping_status')))
            ->when($request->filled('payment_status'), function ($q) use ($request) {
                if ($request->input('payment_status') === 'paid') {
                    $q->where('due', '<=', 0);
                } elseif ($request->input('payment_status') === 'due') {
                    $q->where('due', '>', 0);
                }
            })
            ->when($request->filled('from'), fn ($q) => $q->where('sale_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('sale_date', '<=', $request->input('to')))
            ->paginate(15)
            ->withQueryString();

        return view('shipments.index', compact('shipments'));
    }
}
