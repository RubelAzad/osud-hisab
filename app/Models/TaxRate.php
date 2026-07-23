<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxRate extends Model
{
    use BelongsToPharmacy, HasFactory;

    protected $fillable = ['pharmacy_id', 'name', 'rate', 'status'];

    protected $casts = [
        'rate' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}
