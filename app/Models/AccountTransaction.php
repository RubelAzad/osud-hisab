<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountTransaction extends Model
{
    use BelongsToPharmacy;

    protected $fillable = [
        'pharmacy_id', 'cash_account_id', 'type', 'credit', 'debit', 'reference', 'reference_id', 'transaction_date',
    ];

    protected $casts = [
        'credit' => 'decimal:2',
        'debit' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function cashAccount(): BelongsTo
    {
        return $this->belongsTo(CashAccount::class);
    }
}
