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

class DraftController extends Controller
{
    private const TYPE = Quotation::TYPE_DRAFT;

    public function __construct(private readonly QuotationService $service) {}

    public function index(Request $request): View
    {
        $drafts = Quotation::with(['customer', 'location'])
            ->where('type', self::TYPE)
            ->latest()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('customer_id'), fn ($q) => $q->where('customer_id', $request->integer('customer_id')))
            ->when($request->filled('from'), fn ($q) => $q->where('quotation_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('quotation_date', '<=', $request->input('to')))
            ->paginate(15)
            ->withQueryString();

        return view('drafts.index', compact('drafts'));
    }

    public function create(): View
    {
        return view('drafts.create', $this->formData());
    }

    public function store(QuotationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['location_id'] = $data['location_id'] ?? currentLocationId();

        $draft = $this->service->create($data, auth()->id(), self::TYPE);
        ActivityLog::record('created', $draft);

        return redirect()->route('drafts.show', $draft)->with('success', 'Draft saved.');
    }

    public function show(Quotation $draft): View
    {
        abort_unless($draft->type === self::TYPE, 404);
        $draft->load(['customer', 'location', 'items.medicine']);

        return view('drafts.show', ['draft' => $draft]);
    }

    public function convert(Quotation $draft): RedirectResponse
    {
        abort_unless($draft->type === self::TYPE, 404);

        try {
            $sale = $this->service->convertToSale($draft, auth()->id());
        } catch (InsufficientStockException $e) {
            return back()->with('error', $e->getMessage());
        }

        ActivityLog::record('converted', $draft);

        return redirect()->route('sales.show', $sale)->with('success', 'Draft converted to sale.');
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
