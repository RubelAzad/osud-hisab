<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\StockAdjustmentRequest;
use App\Models\Medicine;
use App\Models\StockAdjustment;
use App\Services\StockAdjustmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockAdjustmentController extends Controller
{
    public function __construct(private readonly StockAdjustmentService $service) {}

    public function index(): View
    {
        $stockAdjustments = StockAdjustment::with(['medicineBatch.medicine'])->latest()->paginate(15);

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
