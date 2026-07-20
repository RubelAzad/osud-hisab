<?php

namespace App\Services;

use App\Models\AccountTransaction;
use App\Models\CashAccount;

class AccountLedgerService
{
    public function post(
        CashAccount $account,
        string $type,
        float $credit,
        float $debit,
        ?string $reference = null,
        ?int $referenceId = null,
        ?string $date = null,
    ): AccountTransaction {
        $account->increment('balance', $credit - $debit);

        return AccountTransaction::create([
            'cash_account_id' => $account->id,
            'type' => $type,
            'credit' => $credit,
            'debit' => $debit,
            'reference' => $reference,
            'reference_id' => $referenceId,
            'transaction_date' => $date ?? now()->toDateString(),
        ]);
    }

    public function defaultAccount(): CashAccount
    {
        return CashAccount::firstOrCreate(['account_name' => 'Cash'], ['balance' => 0]);
    }
}
