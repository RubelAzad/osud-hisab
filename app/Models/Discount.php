<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use BelongsToPharmacy, HasFactory;

    public const TYPE_PERCENTAGE = 'percentage';

    public const TYPE_FIXED = 'fixed';

    public const APPLIES_ALL = 'all';

    public const APPLIES_CATEGORY = 'category';

    public const APPLIES_MEDICINE = 'medicine';

    protected $fillable = [
        'pharmacy_id', 'name', 'type', 'value', 'applies_to',
        'category_id', 'medicine_id', 'starts_at', 'ends_at', 'status',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'status' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function isActive(): bool
    {
        if (! $this->status) {
            return false;
        }

        $today = now()->toDateString();

        if ($this->starts_at && $this->starts_at->toDateString() > $today) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->toDateString() < $today) {
            return false;
        }

        return true;
    }
}
