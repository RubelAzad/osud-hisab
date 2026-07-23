<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class QuotationService
{
    public function __construct(private readonly SaleService $saleService) {}

    /**
     * Pure calculation only — deliberately never touches MedicineBatch/StockMovement,
     * since a quotation/draft is not yet a real transaction. See convertToSale() for
     * the one place inventory actually gets committed.
     */
    public function create(array $data, ?int $createdBy, string $type): Quotation
    {
        return DB::transaction(function () use ($data, $createdBy, $type) {
            $quotation = Quotation::create([
                'customer_id' => $data['customer_id'] ?? null,
                'location_id' => $data['location_id'],
                'type' => $type,
                'quotation_date' => $data['quotation_date'],
                'note' => $data['note'] ?? null,
                'status' => Quotation::STATUS_OPEN,
                'created_by' => $createdBy,
            ]);

            $subtotal = 0;
            $vatTotal = 0;

            foreach ($data['items'] as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                $price = (float) ($item['price'] ?? $medicine->sale_price);
                $qty = (int) $item['qty'];
                $itemDiscount = (float) ($item['discount'] ?? 0);
                $lineTotal = ($qty * $price) - $itemDiscount;

                $quotation->items()->create([
                    'medicine_id' => $medicine->id,
                    'qty' => $qty,
                    'price' => $price,
                    'discount' => $itemDiscount,
                    'total' => $lineTotal,
                ]);

                $subtotal += $lineTotal;
                $vatTotal += round($lineTotal * $medicine->vat / 100, 2);
            }

            $discount = (float) ($data['discount'] ?? 0);
            $total = $subtotal - $discount + $vatTotal;

            $quotation->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat' => $vatTotal,
                'total' => $total,
            ]);

            return $quotation->load('items');
        });
    }

    /**
     * The one place a Quotation/Draft actually becomes a real transaction — hands off
     * to the real SaleService so FIFO batch consumption, stock movements, and invoice
     * numbering all go through the exact same path a direct Sale would.
     */
    public function convertToSale(Quotation $quotation, ?int $createdBy, array $overrides = []): Sale
    {
        return DB::transaction(function () use ($quotation, $createdBy, $overrides) {
            $quotation->load('items');

            $data = [
                'customer_id' => $quotation->customer_id,
                'location_id' => $quotation->location_id,
                'sale_date' => $overrides['sale_date'] ?? now()->format('Y-m-d'),
                'discount' => (float) $quotation->discount,
                'paid' => $overrides['paid'] ?? null,
                'payment_method' => $overrides['payment_method'] ?? 'cash',
                'note' => $quotation->note,
                'channel' => 'manual',
                'items' => $quotation->items->map(fn (QuotationItem $item) => [
                    'medicine_id' => $item->medicine_id,
                    'qty' => $item->qty,
                    'price' => (float) $item->price,
                    'discount' => (float) $item->discount,
                ])->all(),
            ];

            $sale = $this->saleService->create($data, $createdBy);

            $quotation->update(['status' => Quotation::STATUS_CONVERTED, 'sale_id' => $sale->id]);

            return $sale;
        });
    }
}
