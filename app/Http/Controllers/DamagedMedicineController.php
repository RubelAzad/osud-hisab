<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\DamagedMedicineRequest;
use App\Models\DamagedMedicine;
use App\Models\Medicine;
use App\Services\DamagedMedicineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DamagedMedicineController extends Controller
{
    public function __construct(private readonly DamagedMedicineService $service) {}

    public function index(Request $request): View
    {
        $damagedMedicines = DamagedMedicine::with(['medicineBatch.medicine'])
            ->latest()
            ->when($request->filled('q'), fn ($q) => $q->whereHas('medicineBatch.medicine', fn ($q2) => $q2->where('medicine_name', 'like', '%'.$request->string('q').'%')))
            ->when($request->filled('from'), fn ($q) => $q->where('created_at', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('created_at', '<=', $request->input('to').' 23:59:59'))
            ->paginate(15)
            ->withQueryString();

        return view('damaged-medicines.index', compact('damagedMedicines'));
    }

    public function create(): View
    {
        $medicines = Medicine::with(['batches' => fn ($q) => $q->where('remaining_qty', '>', 0)])
            ->whereHas('batches', fn ($q) => $q->where('remaining_qty', '>', 0))
            ->orderBy('medicine_name')
            ->get();

        return view('damaged-medicines.create', compact('medicines'));
    }

    public function store(DamagedMedicineRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated(), auth()->id());
        } catch (InsufficientStockException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('damaged-medicines.index')->with('success', 'Damage recorded and stock adjusted.');
    }
}
