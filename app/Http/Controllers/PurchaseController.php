<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
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
        $purchases = Purchase::with('supplier')->latest()->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    public function create(): View
    {
        $medicines = Medicine::where('status', true)->orderBy('medicine_name')->get();

        return view('purchases.create', [
            'suppliers' => Supplier::where('status', true)->orderBy('name')->get(),
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
        $purchase = $this->purchaseService->create($request->validated(), auth()->id());

        return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase recorded.');
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'items.medicine', 'items.medicineBatch']);

        return view('purchases.show', compact('purchase'));
    }
}
