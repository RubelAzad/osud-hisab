<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use BelongsToPharmacy;

    protected $fillable = [
        'pharmacy_id', 'location_id', 'invoice_no', 'customer_id', 'sale_date', 'subtotal', 'discount',
        'vat', 'total', 'paid', 'due', 'payment_method', 'created_by', 'note',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat' => 'decimal:2',
        'total' => 'decimal:2',
        'paid' => 'decimal:2',
        'due' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (Sale $sale): void {
            if (empty($sale->invoice_no)) {
                $sale->invoice_no = 'INV-'.str_pad((string) $sale->id, 6, '0', STR_PAD_LEFT);
                $sale->saveQuietly();
            }
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(SaleReturn::class);
    }
}
