<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PharmacyRequest;
use App\Models\Pharmacy;
use App\Services\PharmacyOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PharmacyController extends Controller
{
    public function __construct(private readonly PharmacyOnboardingService $onboardingService) {}

    public function index(): View
    {
        $pharmacies = Pharmacy::withCount('users')->latest()->paginate(15);

        return view('super-admin.pharmacies.index', compact('pharmacies'));
    }

    public function create(): View
    {
        return view('super-admin.pharmacies.create');
    }

    public function store(PharmacyRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->onboardingService->register(
            ['name' => $data['name']],
            [
                'name' => $data['owner_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
            ],
        );

        return redirect()->route('super-admin.pharmacies.index')->with('success', 'Pharmacy created.');
    }

    public function toggleStatus(Pharmacy $pharmacy): RedirectResponse
    {
        $pharmacy->update(['status' => ! $pharmacy->status]);

        return back()->with('success', $pharmacy->status ? 'Pharmacy reactivated.' : 'Pharmacy suspended.');
    }
}
