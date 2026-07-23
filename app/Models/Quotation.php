<?php

namespace App\Models;

use App\Models\Concerns\BelongsToPharmacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A saved, non-committed cart — used for both customer-facing Quotations and internal
 * Drafts (see QuotationController/DraftController, both operate on this same model
 * filtered by `type`). Deliberately has no medicine_batch_id on its items and never
 * touches MedicineBatch/StockMovement — nothing here reserves real stock until
 * QuotationService::convertToSale() hands it to the real SaleService.
 */
class Quotation extends Model
{
    use BelongsToPharmacy;

    public const TYPE_QUOTATION = 'quotation';

    public const TYPE_DRAFT = 'draft';

    public const STATUS_OPEN = 'open';

    public const STATUS_CONVERTED = 'converted';

    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'pharmacy_id', 'location_id', 'customer_id', 'type', 'quotation_date',
        'subtotal', 'discount', 'vat', 'total', 'note', 'status', 'sale_id', 'created_by',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'vat' => 'decimal:2',
        'total' => 'decimal:2',
    ];

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
        return $this->hasMany(QuotationItem::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
