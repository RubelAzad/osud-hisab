<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use BelongsToPharmacy;

    protected $fillable = ['pharmacy_id', 'key', 'value'];
}
