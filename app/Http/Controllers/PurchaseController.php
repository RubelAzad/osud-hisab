<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\ActivityLog;
use App\Models\Location;
use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function __construct(private readonly PurchaseService $purchaseService) {}

    public function index(): View
    {
        $purchases = Purchase::with(['supplier', 'location'])->latest()->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    public function create(): View
    {
        $medicines = Medicine::where('status', true)->orderBy('medicine_name')->get();

        return view('purchases.create', [
            'suppliers' => Supplier::where('status', true)->orderBy('name')->get(),
            'locations' => Location::where('status', true)->orderBy('name')->get(),
            'medicinesJson' => $medicines->map(fn (Medicine $m) => [
                'id' => $m->id,
                'name' => $m->medicine_name.' '.$m->strength,
                'purchase_price' => $m->purchase_price,
                'sale_price' => $m->sale_price,
            ])->values(),
        ]);
    }

    public function store(PurchaseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['location_id'] = $data['location_id'] ?? currentLocationId();

        $purchase = $this->purchaseService->create($data, auth()->id());

        ActivityLog::record('created', $purchase);

        return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase recorded.');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'location', 'items.medicine', 'items.medicineBatch']);

        return view('purchases.show', compact('purchase'));
    }
}
