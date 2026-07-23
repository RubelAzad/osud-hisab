<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\SaleRequest;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Manufacturer;
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
            ->with(['category:id,name', 'manufacturer:id,name'])
            ->withSum(['batches as total_stock' => fn ($q) => $q->where('location_id', $locationId)], 'remaining_qty')
            ->having('total_stock', '>', 0)
            ->orderBy('medicine_name')
            ->get();

        $categories = Category::where('status', true)
            ->withCount(['medicines as med_count' => fn ($q) => $q->where('status', true)])
            ->orderBy('name')
            ->get();

        $manufacturers = Manufacturer::where('status', true)
            ->withCount(['medicines as med_count' => fn ($q) => $q->where('status', true)])
            ->orderBy('name')
            ->get();

        $recentSales = Sale::where('channel', 'pos')
            ->with('customer:id,name')
            ->withCount('items')
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn (Sale $s) => [
                'id' => $s->id,
                'invoice_no' => $s->invoice_no,
                'customer' => $s->customer->name ?? 'Walk-in',
                'total' => $s->total,
                'paid' => $s->paid,
                'due' => $s->due,
                'payment_method' => $s->payment_method,
                'items_count' => $s->items_count,
                'date' => $s->sale_date->format('Y-m-d'),
                'time' => $s->created_at->format('H:i'),
            ]);

        return view('pos.index', [
            'customers' => Customer::orderBy('name')->get(),
            'categories' => $categories,
            'manufacturers' => $manufacturers,
            'recentSales' => $recentSales,
            'medicinesJson' => $medicines->map(fn (Medicine $m) => [
                'id' => $m->id,
                'name' => $m->medicine_name . ' ' . $m->strength,
                'barcode' => $m->barcode,
                'price' => $m->sale_price,
                'stock' => $m->total_stock,
                'image' => $m->image ? asset('storage/' . $m->image) : null,
                'category' => $m->category->name ?? '',
                'category_id' => $m->category_id,
                'manufacturer' => $m->manufacturer->name ?? '',
                'manufacturer_id' => $m->manufacturer_id,
            ])->values(),
        ]);
    }

    public function checkout(SaleRequest $request): RedirectResponse|View
    {
        $data = $request->validated();
        $data['location_id'] = $data['location_id'] ?? currentLocationId();
        $data['channel'] = 'pos';

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
