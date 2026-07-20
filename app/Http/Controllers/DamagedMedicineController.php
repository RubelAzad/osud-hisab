<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientStockException;
use App\Http\Requests\DamagedMedicineRequest;
use App\Models\DamagedMedicine;
use App\Models\Medicine;
use App\Services\DamagedMedicineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DamagedMedicineController extends Controller
{
    public function __construct(private readonly DamagedMedicineService $service) {}

    public function index(): View
    {
        $damagedMedicines = DamagedMedicine::with(['medicineBatch.medicine'])->latest()->paginate(15);

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
