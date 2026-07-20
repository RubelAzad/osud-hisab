<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function __construct(private readonly StockMovementService $stockMovementService) {}

    /**
     * @param  array{
     *     supplier_id: int,
     *     purchase_date: string,
     *     discount?: float,
     *     vat?: float,
     *     tax?: float,
     *     paid?: float,
     *     note?: string,
     *     items: array<int, array{medicine_id:int, batch_no:string, qty:int, purchase_price:float, sale_price:float, expiry_date?:string, manufacture_date?:string}>
     * }  $data
     */
    public function create(array $data, ?int $createdBy): Purchase
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $subtotal += $item['qty'] * $item['purchase_price'];
            }

            $discount = (float) ($data['discount'] ?? 0);
            $vat = (float) ($data['vat'] ?? 0);
            $tax = (float) ($data['tax'] ?? 0);
            $total = $subtotal - $discount + $vat + $tax;
            $paid = (float) ($data['paid'] ?? 0);
            $due = $total - $paid;

            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat' => $vat,
                'tax' => $tax,
                'total' => $total,
                'paid' => $paid,
                'due' => $due,
                'note' => $data['note'] ?? null,
                'created_by' => $createdBy,
            ]);

            foreach ($data['items'] as $item) {
                $batch = MedicineBatch::create([
                    'medicine_id' => $item['medicine_id'],
                    'batch_no' => $item['batch_no'],
                    'purchase_price' => $item['purchase_price'],
                    'sale_price' => $item['sale_price'],
                    'quantity' => $item['qty'],
                    'remaining_qty' => $item['qty'],
                    'expiry_date' => $item['expiry_date'] ?? null,
                    'manufacture_date' => $item['manufacture_date'] ?? null,
                    'supplier_id' => $data['supplier_id'],
                ]);

                $purchase->items()->create([
                    'medicine_batch_id' => $batch->id,
                    'medicine_id' => $item['medicine_id'],
                    'qty' => $item['qty'],
                    'purchase_price' => $item['purchase_price'],
                    'sale_price' => $item['sale_price'],
                    'total' => $item['qty'] * $item['purchase_price'],
                ]);

                // Keep the medicine's reference prices current with its latest purchase/sale price.
                Medicine::whereKey($item['medicine_id'])->update([
                    'purchase_price' => $item['purchase_price'],
                    'sale_price' => $item['sale_price'],
                ]);

                $this->stockMovementService->record(
                    medicineId: $item['medicine_id'],
                    batchId: $batch->id,
                    type: StockMovement::TYPE_PURCHASE,
                    qty: $item['qty'],
                    reference: 'purchase',
                    referenceId: $purchase->id,
                );
            }

            Supplier::whereKey($data['supplier_id'])->increment('balance', $due);

            return $purchase->load('items');
        });
    }
}
