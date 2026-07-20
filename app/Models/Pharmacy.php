<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pharmacy extends Model
{
    protected $fillable = [
        'name', 'owner_name', 'phone', 'email', 'address',
        'logo', 'currency', 'timezone', 'vat_percent', 'status',
    ];

    protected $casts = [
        'vat_percent' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
