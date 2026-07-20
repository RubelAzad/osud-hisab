<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    public function __construct(private readonly AccountLedgerService $ledger) {}

    public function create(array $data): Expense
    {
        return DB::transaction(function () use ($data) {
            $expense = Expense::create($data);

            $this->ledger->post(
                account: $this->ledger->defaultAccount(),
                type: 'Expense',
                credit: 0,
                debit: (float) $expense->amount,
                reference: 'expense',
                referenceId: $expense->id,
                date: $expense->expense_date->toDateString(),
            );

            return $expense;
        });
    }

    public function update(Expense $expense, array $data): Expense
    {
        return DB::transaction(function () use ($expense, $data) {
            $oldAmount = (float) $expense->amount;
            $expense->update($data);
            $delta = (float) $expense->amount - $oldAmount;

            if ($delta !== 0.0) {
                $this->ledger->post(
                    account: $this->ledger->defaultAccount(),
                    type: 'Expense Adjustment',
                    credit: $delta < 0 ? abs($delta) : 0,
                    debit: $delta > 0 ? $delta : 0,
                    reference: 'expense',
                    referenceId: $expense->id,
                );
            }

            return $expense;
        });
    }

    public function delete(Expense $expense): void
    {
        DB::transaction(function () use ($expense) {
            $this->ledger->post(
                account: $this->ledger->defaultAccount(),
                type: 'Expense Reversal',
                credit: (float) $expense->amount,
                debit: 0,
                reference: 'expense',
                referenceId: $expense->id,
            );

            $expense->delete();
        });
    }
}
