<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use BelongsToPharmacy;

    protected $fillable = ['pharmacy_id', 'name', 'address', 'phone', 'is_default', 'status'];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
    ];

    public function medicineBatches(): HasMany
    {
        return $this->hasMany(MedicineBatch::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
