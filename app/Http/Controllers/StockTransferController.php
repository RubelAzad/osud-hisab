<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\StockTransferRequest;
use App\Models\Location;
use App\Models\Medicine;
use App\Models\StockTransfer;
use App\Services\StockTransferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StockTransferController extends Controller
{
    public function __construct(private readonly StockTransferService $service) {}

    public function index(): View
    {
        $stockTransfers = StockTransfer::with(['fromLocation', 'toLocation'])->latest()->paginate(15);

        return view('stock-transfers.index', compact('stockTransfers'));
    }

    public function create(): View
    {
        $medicines = Medicine::where('status', true)->orderBy('medicine_name')->get();

        return view('stock-transfers.create', [
            'locations' => Location::where('status', true)->orderBy('name')->get(),
            'medicinesJson' => $medicines->map(fn (Medicine $m) => [
                'id' => $m->id,
                'name' => $m->medicine_name.' '.$m->strength,
            ])->values(),
        ]);
    }

    public function store(StockTransferRequest $request): RedirectResponse
    {
        try {
            $transfer = $this->service->create($request->validated(), auth()->id());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('stock-transfers.show', $transfer)->with('success', 'Stock transfer recorded.');
    }

    public function show(StockTransfer $stock_transfer): View
    {
        $stock_transfer->load(['fromLocation', 'toLocation', 'items.medicine']);

        return view('stock-transfers.show', ['stockTransfer' => $stock_transfer]);
    }
}
