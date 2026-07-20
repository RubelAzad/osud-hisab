<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\SaleRequest;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(private readonly SaleService $saleService) {}

    public function index(): View
    {
        $sales = Sale::with('customer')->latest()->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $medicines = Medicine::where('status', true)
            ->withSum('batches as total_stock', 'remaining_qty')
            ->having('total_stock', '>', 0)
            ->orderBy('medicine_name')
            ->get();

        return view('sales.create', [
            'customers' => Customer::orderBy('name')->get(),
            'medicinesJson' => $medicines->map(fn (Medicine $m) => [
                'id' => $m->id,
                'name' => $m->medicine_name.' '.$m->strength,
                'price' => $m->sale_price,
                'stock' => $m->total_stock,
            ])->values(),
        ]);
    }

    public function store(SaleRequest $request): RedirectResponse
    {
        try {
            $sale = $this->saleService->create($request->validated(), auth()->id());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('sales.show', $sale)->with('success', 'Sale recorded.');
    }

    public function show(Sale $sale): View
    {
        $sale->load(['customer', 'items.medicine', 'items.medicineBatch']);

        return view('sales.show', compact('sale'));
    }
}
