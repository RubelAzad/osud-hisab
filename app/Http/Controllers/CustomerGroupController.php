<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerGroupRequest;
use App\Models\CustomerGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerGroupController extends Controller
{
    public function index(): View
    {
        $customerGroups = CustomerGroup::withCount('customers')->latest()->paginate(15);

        return view('customer-groups.index', compact('customerGroups'));
    }

    public function create(): View
    {
        return view('customer-groups.create');
    }

    public function store(CustomerGroupRequest $request): RedirectResponse
    {
        CustomerGroup::create($request->validated());

        return redirect()->route('customer-groups.index')->with('success', 'Customer group created.');
    }

    public function edit(CustomerGroup $customerGroup): View
    {
        return view('customer-groups.edit', compact('customerGroup'));
    }

    public function update(CustomerGroupRequest $request, CustomerGroup $customerGroup): RedirectResponse
    {
        $customerGroup->update($request->validated());

        return redirect()->route('customer-groups.index')->with('success', 'Customer group updated.');
    }

    public function destroy(CustomerGroup $customerGroup): RedirectResponse
    {
        if ($customerGroup->customers()->exists()) {
            return back()->with('error', 'Cannot delete a customer group that has customers assigned.');
        }

        $customerGroup->delete();

        return redirect()->route('customer-groups.index')->with('success', 'Customer group deleted.');
    }
}
