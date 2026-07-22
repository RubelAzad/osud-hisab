<?php

namespace App\Services;

use App\Exceptions\InvalidReturnException;
use App\Models\Customer;
use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class SaleReturnService
{
    public function __construct(private readonly StockMovementService $stockMovementService) {}

    /**
     * @param  array{
     *     return_date: string,
     *     reason?: string,
     *     items: array<int, array{medicine_batch_id:int, qty:int}>
     * }  $data
     */
    public function create(Sale $sale, array $data): SaleReturn
    {
        return DB::transaction(function () use ($sale, $data) {
            $saleReturn = SaleReturn::create([
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'return_date' => $data['return_date'],
                'reason' => $data['reason'] ?? null,
            ]);

            $refundTotal = 0;

            foreach ($data['items'] as $item) {
                $qty = (int) $item['qty'];

                if ($qty <= 0) {
                    continue;
                }

                $saleItem = SaleItem::where('sale_id', $sale->id)
                    ->where('medicine_batch_id', $item['medicine_batch_id'])
                    ->first();

                if (! $saleItem) {
                    throw new InvalidReturnException('That item was not part of this sale.');
                }

                $alreadyReturned = SaleReturnItem::whereHas(
                    'saleReturn',
                    fn ($q) => $q->where('sale_id', $sale->id)
                )->where('medicine_batch_id', $item['medicine_batch_id'])->sum('qty');

                $returnable = $saleItem->qty - $alreadyReturned;

                if ($qty > $returnable) {
                    throw new InvalidReturnException(
                        "Cannot return {$qty} units of {$saleItem->medicine->medicine_name}: only {$returnable} remain returnable."
                    );
                }

                $saleReturn->items()->create([
                    'medicine_batch_id' => $item['medicine_batch_id'],
                    'qty' => $qty,
                    'price' => $saleItem->price,
                ]);

                MedicineBatch::whereKey($item['medicine_batch_id'])->increment('remaining_qty', $qty);

                $this->stockMovementService->record(
                    medicineId: $saleItem->medicine_id,
                    batchId: $item['medicine_batch_id'],
                    type: StockMovement::TYPE_RETURN,
                    qty: $qty,
                    locationId: $sale->location_id,
                    reference: 'sale_return',
                    referenceId: $saleReturn->id,
                );

                $refundTotal += $qty * $saleItem->price;
            }

            if ($refundTotal <= 0) {
                throw new InvalidReturnException('Select at least one item to return.');
            }

            $saleReturn->update(['refund_amount' => $refundTotal]);

            if ($sale->customer_id) {
                Customer::whereKey($sale->customer_id)->decrement('balance', $refundTotal);
            }

            return $saleReturn->load('items');
        });
    }
}
