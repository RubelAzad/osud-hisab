<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\SaleRequest;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Medicine;
use App\Models\Sale;
use App\Services\SaleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(private readonly SaleService $saleService) {}

    public function index(): View
    {
        $sales = Sale::with(['customer', 'location'])->latest()->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $locationId = currentLocationId();

        $medicines = Medicine::where('status', true)
            ->withSum(['batches as total_stock' => fn ($q) => $q->where('location_id', $locationId)], 'remaining_qty')
            ->having('total_stock', '>', 0)
            ->orderBy('medicine_name')
            ->get();

        return view('sales.create', [
            'customers' => Customer::orderBy('name')->get(),
            'locations' => Location::where('status', true)->orderBy('name')->get(),
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
        $data = $request->validated();
        $data['location_id'] = $data['location_id'] ?? currentLocationId();

        try {
            $sale = $this->saleService->create($data, auth()->id());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('sales.show', $sale)->with('success', 'Sale recorded.');
    }

    public function show(Sale $sale): View
    {
        $sale->load(['customer', 'location', 'items.medicine', 'items.medicineBatch']);

        return view('sales.show', compact('sale'));
    }

    public function downloadInvoice(Sale $sale)
    {
        $sale->load(['customer', 'location', 'items.medicine']);

        $pdf = Pdf::loadView('pdf.invoice', ['sale' => $sale, 'pharmacy' => currentPharmacy()]);

        return $pdf->download("invoice-{$sale->invoice_no}.pdf");
    }
}
