<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\QuotationRequest;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Medicine;
use App\Models\Quotation;
use App\Services\QuotationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuotationController extends Controller
{
    private const TYPE = Quotation::TYPE_QUOTATION;

    public function __construct(private readonly QuotationService $service) {}

    public function index(Request $request): View
    {
        $quotations = Quotation::with(['customer', 'location'])
            ->where('type', self::TYPE)
            ->latest()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('customer_id'), fn ($q) => $q->where('customer_id', $request->integer('customer_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('quotation_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('quotation_date', '<=', $request->input('to')))
            ->paginate(15)
            ->withQueryString();

        return view('quotations.index', compact('quotations'));
    }

    public function create(): View
    {
        return view('quotations.create', $this->formData());
    }

    public function store(QuotationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['location_id'] = $data['location_id'] ?? currentLocationId();

        $quotation = $this->service->create($data, auth()->id(), self::TYPE);
        ActivityLog::record('created', $quotation);

        return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation saved.');
    }

    public function show(Quotation $quotation): View
    {
        abort_unless($quotation->type === self::TYPE, 404);
        $quotation->load(['customer', 'location', 'items.medicine']);

        return view('quotations.show', compact('quotation'));
    }

    public function convert(Quotation $quotation): RedirectResponse
    {
        abort_unless($quotation->type === self::TYPE, 404);

        try {
            $sale = $this->service->convertToSale($quotation, auth()->id());
        } catch (InsufficientStockException $e) {
            return back()->with('error', $e->getMessage());
        }

        ActivityLog::record('converted', $quotation);

        return redirect()->route('sales.show', $sale)->with('success', 'Quotation converted to sale.');
    }

    private function formData(): array
    {
        $medicines = Medicine::where('status', true)->orderBy('medicine_name')->get();

        return [
            'customers' => Customer::orderBy('name')->get(),
            'locations' => Location::where('status', true)->orderBy('name')->get(),
            'medicinesJson' => $medicines->map(fn (Medicine $m) => [
                'id' => $m->id,
                'name' => $m->medicine_name.' '.$m->strength,
                'price' => $m->sale_price,
            ])->values(),
        ];
    }
}
