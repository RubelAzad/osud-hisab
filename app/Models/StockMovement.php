<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use BelongsToPharmacy;

    public const TYPE_PURCHASE = 'Purchase';

    public const TYPE_SALE = 'Sale';

    public const TYPE_RETURN = 'Return';

    public const TYPE_DAMAGE = 'Damage';

    public const TYPE_ADJUSTMENT = 'Adjustment';

    protected $fillable = [
        'pharmacy_id', 'medicine_id', 'batch_id', 'type', 'qty', 'reference', 'reference_id',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(MedicineBatch::class, 'batch_id');
    }
}
