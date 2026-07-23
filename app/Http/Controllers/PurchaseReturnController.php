<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidReturnException;
use App\Http\Requests\PurchaseReturnRequest;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Services\PurchaseReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseReturnController extends Controller
{
    public function __construct(private readonly PurchaseReturnService $service) {}

    public function index(Request $request): View
    {
        $purchaseReturns = PurchaseReturn::with(['purchase', 'supplier'])
            ->latest()
            ->when($request->filled('supplier_id'), fn ($q) => $q->where('supplier_id', $request->integer('supplier_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('return_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('return_date', '<=', $request->input('to')))
            ->paginate(15)
            ->withQueryString();

        return view('purchase-returns.index', compact('purchaseReturns'));
    }

    public function create(Purchase $purchase): View
    {
        $purchase->load(['items.medicine', 'items.medicineBatch', 'supplier']);

        return view('purchase-returns.create', compact('purchase'));
    }

    public function store(PurchaseReturnRequest $request, Purchase $purchase): RedirectResponse
    {
        try {
            $purchaseReturn = $this->service->create($purchase, $request->validated());
        } catch (InvalidReturnException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('purchase-returns.show', $purchaseReturn)->with('success', 'Purchase return recorded.');
    }

    public function show(PurchaseReturn $purchase_return): View
    {
        $purchase_return->load(['purchase', 'supplier', 'items.medicineBatch.medicine']);

        return view('purchase-returns.show', ['purchaseReturn' => $purchase_return]);
    }
}
