<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountRequest;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Medicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function index(): View
    {
        $discounts = Discount::with(['category', 'medicine'])->latest()->paginate(15);

        return view('discounts.index', compact('discounts'));
    }

    public function create(): View
    {
        return view('discounts.create', $this->formData());
    }

    public function store(DiscountRequest $request): RedirectResponse
    {
        Discount::create($request->validated());

        return redirect()->route('discounts.index')->with('success', 'Discount created.');
    }

    public function edit(Discount $discount): View
    {
        return view('discounts.edit', array_merge(['discount' => $discount], $this->formData()));
    }

    public function update(DiscountRequest $request, Discount $discount): RedirectResponse
    {
        $discount->update($request->validated());

        return redirect()->route('discounts.index')->with('success', 'Discount updated.');
    }

    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();

        return redirect()->route('discounts.index')->with('success', 'Discount deleted.');
    }

    private function formData(): array
    {
        return [
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'medicines' => Medicine::where('status', true)->orderBy('medicine_name')->get(),
        ];
    }
}
