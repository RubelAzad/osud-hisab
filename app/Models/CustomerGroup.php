<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerGroup extends Model
{
    use BelongsToPharmacy, HasFactory;

    protected $fillable = ['pharmacy_id', 'name'];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
