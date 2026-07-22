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

class PosController extends Controller
{
    public function __construct(private readonly SaleService $saleService) {}

    public function index(): View
    {
        $locationId = currentLocationId();

        $medicines = Medicine::where('status', true)
            ->withSum(['batches as total_stock' => fn ($q) => $q->where('location_id', $locationId)], 'remaining_qty')
            ->having('total_stock', '>', 0)
            ->orderBy('medicine_name')
            ->get();

        return view('pos.index', [
            'customers' => Customer::orderBy('name')->get(),
            'medicinesJson' => $medicines->map(fn (Medicine $m) => [
                'id' => $m->id,
                'name' => $m->medicine_name.' '.$m->strength,
                'barcode' => $m->barcode,
                'price' => $m->sale_price,
                'stock' => $m->total_stock,
            ])->values(),
        ]);
    }

    public function checkout(SaleRequest $request): RedirectResponse|View
    {
        $data = $request->validated();
        $data['location_id'] = $data['location_id'] ?? currentLocationId();

        try {
            $sale = $this->saleService->create($data, auth()->id());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('pos.receipt', $sale);
    }

    public function receipt(Sale $sale): View
    {
        $sale->load(['customer', 'location', 'items.medicine', 'items.medicineBatch']);

        return view('pos.receipt', compact('sale'));
    }
}
