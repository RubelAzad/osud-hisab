<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidReturnException;
use App\Http\Requests\SaleReturnRequest;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Services\SaleReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleReturnController extends Controller
{
    public function __construct(private readonly SaleReturnService $service) {}

    public function index(Request $request): View
    {
        $saleReturns = SaleReturn::with(['sale', 'customer'])
            ->latest()
            ->when($request->filled('customer_id'), fn ($q) => $q->where('customer_id', $request->integer('customer_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('return_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('return_date', '<=', $request->input('to')))
            ->paginate(15)
            ->withQueryString();

        return view('sale-returns.index', compact('saleReturns'));
    }

    public function create(Sale $sale): View
    {
        $sale->load(['items.medicine', 'items.medicineBatch', 'customer']);

        return view('sale-returns.create', compact('sale'));
    }

    public function store(SaleReturnRequest $request, Sale $sale): RedirectResponse
    {
        try {
            $saleReturn = $this->service->create($sale, $request->validated());
        } catch (InvalidReturnException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('sale-returns.show', $saleReturn)->with('success', 'Sale return recorded.');
    }

    public function show(SaleReturn $sale_return): View
    {
        $sale_return->load(['sale', 'customer', 'items.medicineBatch.medicine']);

        return view('sale-returns.show', ['saleReturn' => $sale_return]);
    }
}
