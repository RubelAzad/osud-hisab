<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseCategory extends Model
{
    use BelongsToPharmacy;

    protected $fillable = ['pharmacy_id', 'name'];

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
