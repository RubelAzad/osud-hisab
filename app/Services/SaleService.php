<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(private readonly StockMovementService $stockMovementService) {}

    /**
     * @param  array{
     *     customer_id?: int|null,
     *     sale_date: string,
     *     discount?: float,
     *     paid?: float,
     *     payment_method?: string,
     *     note?: string,
     *     items: array<int, array{medicine_id:int, qty:int, price?:float, discount?:float}>
     * }  $data
     */
    public function create(array $data, ?int $createdBy): Sale
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $sale = Sale::create([
                'customer_id' => $data['customer_id'] ?? null,
                'sale_date' => $data['sale_date'],
                'payment_method' => $data['payment_method'] ?? 'cash',
                'note' => $data['note'] ?? null,
                'created_by' => $createdBy,
            ]);

            $subtotal = 0;
            $vatTotal = 0;

            foreach ($data['items'] as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);
                $price = (float) ($item['price'] ?? $medicine->sale_price);
                $itemDiscount = (float) ($item['discount'] ?? 0);
                $qtyRemaining = (int) $item['qty'];

                $batches = MedicineBatch::where('medicine_id', $medicine->id)
                    ->where('remaining_qty', '>', 0)
                    ->orderBy('created_at')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $available = $batches->sum('remaining_qty');

                if ($available < $qtyRemaining) {
                    throw new InsufficientStockException(
                        "Insufficient stock for {$medicine->medicine_name}: requested {$qtyRemaining}, available {$available}."
                    );
                }

                foreach ($batches as $batch) {
                    if ($qtyRemaining <= 0) {
                        break;
                    }

                    $qtyFromBatch = min($qtyRemaining, $batch->remaining_qty);
                    $lineTotal = ($qtyFromBatch * $price) - ($itemDiscount * ($qtyFromBatch / $item['qty']));

                    $sale->items()->create([
                        'medicine_batch_id' => $batch->id,
                        'medicine_id' => $medicine->id,
                        'qty' => $qtyFromBatch,
                        'price' => $price,
                        'discount' => $itemDiscount * ($qtyFromBatch / $item['qty']),
                        'total' => $lineTotal,
                    ]);

                    $batch->decrement('remaining_qty', $qtyFromBatch);

                    $this->stockMovementService->record(
                        medicineId: $medicine->id,
                        batchId: $batch->id,
                        type: StockMovement::TYPE_SALE,
                        qty: -$qtyFromBatch,
                        reference: 'sale',
                        referenceId: $sale->id,
                    );

                    $subtotal += $lineTotal;
                    $vatTotal += round($lineTotal * $medicine->vat / 100, 2);
                    $qtyRemaining -= $qtyFromBatch;
                }
            }

            $discount = (float) ($data['discount'] ?? 0);
            $vat = $vatTotal;
            $total = $subtotal - $discount + $vat;
            $paid = (float) ($data['paid'] ?? $total);
            $due = $total - $paid;

            $sale->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat' => $vat,
                'total' => $total,
                'paid' => $paid,
                'due' => $due,
            ]);

            if (! empty($data['customer_id'])) {
                Customer::whereKey($data['customer_id'])->increment('balance', $due);
            }

            return $sale->load('items');
        });
    }
}
