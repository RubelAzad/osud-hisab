<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\SaleRequest;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Location;
use App\Models\Medicine;
use App\Models\PriceGroup;
use App\Models\Sale;
use App\Services\SaleService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(private readonly SaleService $saleService) {}

    public function index(Request $request): View
    {
        $sales = Sale::with(['customer', 'location'])
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->where('invoice_no', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('customer_id'), fn ($q) => $q->where('customer_id', $request->integer('customer_id')))
            ->when($request->filled('location_id'), fn ($q) => $q->where('location_id', $request->integer('location_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('sale_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('sale_date', '<=', $request->input('to')))
            ->when($request->filled('payment_status'), function ($q) use ($request) {
                if ($request->input('payment_status') === 'paid') {
                    $q->where('due', '<=', 0);
                } elseif ($request->input('payment_status') === 'due') {
                    $q->where('due', '>', 0);
                }
            })
            ->paginate(15)
            ->withQueryString();

        return view('sales.index', compact('sales'));
    }

    public function create(Request $request): View
    {
        $locationId = currentLocationId();
        $priceGroupId = $request->integer('price_group_id') ?: null;

        $medicines = Medicine::where('status', true)
            ->withSum(['batches as total_stock' => fn ($q) => $q->where('location_id', $locationId)], 'remaining_qty')
            ->having('total_stock', '>', 0)
            ->with(['priceGroups' => fn ($q) => $priceGroupId ? $q->where('price_groups.id', $priceGroupId) : $q->whereRaw('1=0')])
            ->orderBy('medicine_name')
            ->get();

        return view('sales.create', [
            'customers' => Customer::orderBy('name')->get(),
            'locations' => Location::where('status', true)->orderBy('name')->get(),
            'priceGroups' => PriceGroup::where('status', true)->orderBy('name')->get(),
            'activeDiscounts' => Discount::where('status', true)->get()->filter->isActive()->values(),
            'medicinesJson' => $medicines->map(fn (Medicine $m) => [
                'id' => $m->id,
                'name' => $m->medicine_name.' '.$m->strength,
                'price' => $priceGroupId && $m->priceGroups->isNotEmpty() ? $m->priceGroups->first()->pivot->price : $m->sale_price,
                'stock' => $m->total_stock,
                'category_id' => $m->category_id,
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

        ActivityLog::record('created', $sale);

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

    public function updateShippingStatus(Request $request, Sale $sale): RedirectResponse
    {
        $data = $request->validate([
            'shipping_status' => ['required', Rule::in(['pending', 'shipped', 'delivered'])],
            'shipping_address' => ['nullable', 'string'],
        ]);

        $data['shipped_at'] = $data['shipping_status'] === 'shipped' ? now() : $sale->shipped_at;

        $sale->update($data);
        ActivityLog::record('updated', $sale);

        return back()->with('success', 'Shipping status updated.');
    }
}
