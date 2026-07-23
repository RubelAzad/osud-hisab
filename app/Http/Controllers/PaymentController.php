<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Supplier;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function index(): View
    {
        $payments = Payment::with(['customer', 'supplier'])->latest('payment_date')->latest('id')->paginate(15);

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
