<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    use BelongsToPharmacy;

    public const TYPE_INCREASE = 'increase';

    public const TYPE_DECREASE = 'decrease';

    protected $fillable = ['pharmacy_id', 'location_id', 'medicine_batch_id', 'type', 'qty', 'reason', 'created_by'];

    public function medicineBatch(): BelongsTo
    {
        return $this->belongsTo(MedicineBatch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
