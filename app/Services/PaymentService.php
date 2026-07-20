<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(private readonly AccountLedgerService $ledger) {}

    public function payCustomer(Customer $customer, array $data): Payment
    {
        return DB::transaction(function () use ($customer, $data) {
            $payment = Payment::create(array_merge($data, ['customer_id' => $customer->id]));

            Customer::whereKey($customer->id)->decrement('balance', (float) $data['amount']);

            $this->ledger->post(
                account: $this->ledger->defaultAccount(),
                type: 'Customer Payment',
                credit: (float) $data['amount'],
                debit: 0,
                reference: 'payment',
                referenceId: $payment->id,
                date: $data['payment_date'],
            );

            return $payment;
        });
    }

    public function paySupplier(Supplier $supplier, array $data): Payment
    {
        return DB::transaction(function () use ($supplier, $data) {
            $payment = Payment::create(array_merge($data, ['supplier_id' => $supplier->id]));

            Supplier::whereKey($supplier->id)->decrement('balance', (float) $data['amount']);

            $this->ledger->post(
                account: $this->ledger->defaultAccount(),
                type: 'Supplier Payment',
                credit: 0,
                debit: (float) $data['amount'],
                reference: 'payment',
                referenceId: $payment->id,
                date: $data['payment_date'],
            );

            return $payment;
        });
    }
}
