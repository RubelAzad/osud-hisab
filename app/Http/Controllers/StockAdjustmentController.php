<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\StockAdjustmentRequest;
use App\Models\Medicine;
use App\Models\StockAdjustment;
use App\Services\StockAdjustmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockAdjustmentController extends Controller
{
    public function __construct(private readonly StockAdjustmentService $service) {}

    public function index(Request $request): View
    {
        $stockAdjustments = StockAdjustment::with(['medicineBatch.medicine'])
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->whereHas('medicineBatch.medicine', fn ($q2) => $q2->where('medicine_name', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->when($request->filled('from'), fn ($q) => $q->where('created_at', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('created_at', '<=', $request->input('to').' 23:59:59'))
            ->paginate(15)
            ->withQueryString();

        return view('stock-adjustments.index', compact('stockAdjustments'));
    }

    public function create(): View
    {
        $medicines = Medicine::with(['batches' => fn ($q) => $q->where('remaining_qty', '>', 0)])
            ->orderBy('medicine_name')
            ->get();

        return view('stock-adjustments.create', compact('medicines'));
    }

    public function store(StockAdjustmentRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated(), auth()->id());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('stock-adjustments.index')->with('success', 'Stock adjusted.');
    }
}
