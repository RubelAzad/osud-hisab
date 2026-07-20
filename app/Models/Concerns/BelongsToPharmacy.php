<?php

namespace App\Models\Concerns;

use App\Models\Pharmacy;
use App\Models\Scopes\PharmacyScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToPharmacy
{
    protected static function bootBelongsToPharmacy(): void
    {
        static::addGlobalScope(new PharmacyScope);

        static::creating(function ($model): void {
            if (empty($model->pharmacy_id) && $pharmacyId = currentPharmacyId()) {
                $model->pharmacy_id = $pharmacyId;
            }
        });
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }
}
