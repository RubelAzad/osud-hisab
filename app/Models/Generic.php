<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generic extends Model
{
    use BelongsToPharmacy;

    protected $fillable = ['pharmacy_id', 'name', 'description'];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class);
    }
}
