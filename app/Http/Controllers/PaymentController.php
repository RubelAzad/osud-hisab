<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Supplier;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function index(Request $request): View
    {
        $payments = Payment::with(['customer', 'supplier'])
            ->latest('payment_date')->latest('id')
            ->when($request->filled('party_type'), function ($q) use ($request) {
                if ($request->input('party_type') === 'customer') {
                    $q->whereNotNull('customer_id');
                } elseif ($request->input('party_type') === 'supplier') {
                    $q->whereNotNull('supplier_id');
                }
            })
            ->when($request->filled('payment_method'), fn ($q) => $q->where('payment_method', $request->input('payment_method')))
            ->when($request->filled('from'), fn ($q) => $q->where('payment_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->where('payment_date', '<=', $request->input('to')))
            ->paginate(15)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function createForCustomer(Customer $customer): View
    {
        return view('payments.create-customer', compact('customer'));
    }

    public function storeForCustomer(PaymentRequest $request, Customer $customer): RedirectResponse
    {
        $payment = $this->paymentService->payCustomer($customer, $request->validated());
        ActivityLog::record('created', $payment);

        return redirect()->route('customers.show', $customer)->with('success', 'Payment recorded.');
    }

    public function createForSupplier(Supplier $supplier): View
    {
        return view('payments.create-supplier', compact('supplier'));
    }

    public function storeForSupplier(PaymentRequest $request, Supplier $supplier): RedirectResponse
    {
        $payment = $this->paymentService->paySupplier($supplier, $request->validated());
        ActivityLog::record('created', $payment);

        return redirect()->route('suppliers.show', $supplier)->with('success', 'Payment recorded.');
    }
}
