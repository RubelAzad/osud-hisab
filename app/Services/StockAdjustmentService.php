<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\MedicineBatch;
use App\Models\StockAdjustment;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockAdjustmentService
{
    public function __construct(private readonly StockMovementService $stockMovementService) {}

    public function create(array $data, ?int $createdBy): StockAdjustment
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $batch = MedicineBatch::findOrFail($data['medicine_batch_id']);
            $qty = (int) $data['qty'];
            $type = $data['type'];

            if ($type === StockAdjustment::TYPE_DECREASE && $qty > $batch->remaining_qty) {
                throw new InsufficientStockException(
                    "Only {$batch->remaining_qty} units remain in batch {$batch->batch_no}."
                );
            }

            $adjustment = StockAdjustment::create([
                'medicine_batch_id' => $batch->id,
                'location_id' => $batch->location_id,
                'type' => $type,
                'qty' => $qty,
                'reason' => $data['reason'] ?? null,
                'created_by' => $createdBy,
            ]);

            $signedQty = $type === StockAdjustment::TYPE_INCREASE ? $qty : -$qty;
            $batch->increment('remaining_qty', $signedQty);

            $this->stockMovementService->record(
                medicineId: $batch->medicine_id,
                batchId: $batch->id,
                type: StockMovement::TYPE_ADJUSTMENT,
                qty: $signedQty,
                locationId: $batch->location_id,
                reference: 'stock_adjustment',
                referenceId: $adjustment->id,
            );

            return $adjustment;
        });
    }
}
