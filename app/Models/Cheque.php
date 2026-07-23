<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheque extends Model
{
    use BelongsToPharmacy;

    public const STATUS_PENDING = 'pending';

    public const STATUS_CLEARED = 'cleared';

    public const STATUS_BOUNCED = 'bounced';

    protected $fillable = ['pharmacy_id', 'payment_id', 'cheque_no', 'bank_name', 'cheque_date', 'due_date', 'status'];

    protected $casts = [
        'cheque_date' => 'date',
        'due_date' => 'date',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
