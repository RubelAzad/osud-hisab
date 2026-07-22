<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use BelongsToPharmacy;

    protected $fillable = [
        'pharmacy_id', 'location_id', 'supplier_id', 'invoice_no', 'purchase_date', 'subtotal', 'discount',
        'vat', 'tax', 'total', 'paid', 'due', 'note', 'created_by',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid' => 'decimal:2',
        'due' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (Purchase $purchase): void {
            if (empty($purchase->invoice_no)) {
                $purchase->invoice_no = 'PINV-'.str_pad((string) $purchase->id, 6, '0', STR_PAD_LEFT);
                $purchase->saveQuietly();
            }
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(PurchaseReturn::class);
    }
}
