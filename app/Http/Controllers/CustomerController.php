<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\CustomerGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::latest()->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create', ['customerGroups' => CustomerGroup::orderBy('name')->get()]);
    }

    public function store(CustomerRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['balance'] = $data['opening_balance'];

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer created.');
    }

    public function show(Customer $customer): View
    {
        $sales = $customer->sales()->latest()->paginate(10, ['*'], 'sales_page');
        $payments = $customer->payments()->latest('payment_date')->latest('id')->paginate(10, ['*'], 'payments_page');

        return view('customers.show', compact('customer', 'sales', 'payments'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', ['customer' => $customer, 'customerGroups' => CustomerGroup::orderBy('name')->get()]);
    }

    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();
        unset($data['opening_balance']);

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->sales()->exists()) {
            return back()->with('error', 'Cannot delete a customer that has sales.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }
}
