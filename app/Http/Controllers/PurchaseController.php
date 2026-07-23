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
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function __construct(private readonly PurchaseService $purchaseService) {}

    public function index(Request $request): View
    {
        $purchases = Purchase::with(['supplier', 'location'])
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->where('invoice_no', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('supplier_id'), fn ($q) => $q->where('supplier_id', $request->integer('supplier_id')))
            ->when($request->filled('location_id'), fn ($q) => $q->where('location_id', $request->integer('location_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('purchase_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('purchase_date', '<=', $request->input('to')))
            ->when($request->filled('payment_status'), function ($q) use ($request) {
                if ($request->input('payment_status') === 'paid') {
                    $q->where('due', '<=', 0);
                } elseif ($request->input('payment_status') === 'due') {
                    $q->where('due', '>', 0);
                }
            })
            ->paginate(15)
            ->withQueryString();

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
