<?php

namespace App\Services;

use App\Exceptions\InvalidReturnException;
use App\Models\MedicineBatch;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class PurchaseReturnService
{
    public function __construct(private readonly StockMovementService $stockMovementService) {}

    /**
     * @param  array{
     *     return_date: string,
     *     items: array<int, array{medicine_batch_id:int, qty:int}>
     * }  $data
     */
    public function create(Purchase $purchase, array $data): PurchaseReturn
    {
        return DB::transaction(function () use ($purchase, $data) {
            $purchaseReturn = PurchaseReturn::create([
                'purchase_id' => $purchase->id,
                'supplier_id' => $purchase->supplier_id,
                'return_date' => $data['return_date'],
            ]);

            $amountTotal = 0;

            foreach ($data['items'] as $item) {
                $qty = (int) $item['qty'];

                if ($qty <= 0) {
                    continue;
                }

                $purchaseItem = PurchaseItem::where('purchase_id', $purchase->id)
                    ->where('medicine_batch_id', $item['medicine_batch_id'])
                    ->first();

                if (! $purchaseItem) {
                    throw new InvalidReturnException('That item was not part of this purchase.');
                }

                $alreadyReturned = PurchaseReturnItem::whereHas(
                    'purchaseReturn',
                    fn ($q) => $q->where('purchase_id', $purchase->id)
                )->where('medicine_batch_id', $item['medicine_batch_id'])->sum('qty');

                $batch = MedicineBatch::findOrFail($item['medicine_batch_id']);
                $returnable = min($purchaseItem->qty - $alreadyReturned, $batch->remaining_qty);

                if ($qty > $returnable) {
                    throw new InvalidReturnException(
                        "Cannot return {$qty} units of {$purchaseItem->medicine->medicine_name}: only {$returnable} remain returnable."
                    );
                }

                $purchaseReturn->items()->create([
                    'medicine_batch_id' => $item['medicine_batch_id'],
                    'qty' => $qty,
                    'price' => $purchaseItem->purchase_price,
                ]);

                $batch->decrement('remaining_qty', $qty);

                $this->stockMovementService->record(
                    medicineId: $purchaseItem->medicine_id,
                    batchId: $item['medicine_batch_id'],
                    type: StockMovement::TYPE_RETURN,
                    qty: -$qty,
                    reference: 'purchase_return',
                    referenceId: $purchaseReturn->id,
                );

                $amountTotal += $qty * $purchaseItem->purchase_price;
            }

            if ($amountTotal <= 0) {
                throw new InvalidReturnException('Select at least one item to return.');
            }

            $purchaseReturn->update(['amount' => $amountTotal]);

            Supplier::whereKey($purchase->supplier_id)->decrement('balance', $amountTotal);

            return $purchaseReturn->load('items');
        });
    }
}
