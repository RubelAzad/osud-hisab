<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\DamagedMedicine;
use App\Models\MedicineBatch;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class DamagedMedicineService
{
    public function __construct(private readonly StockMovementService $stockMovementService) {}

    public function create(array $data, ?int $createdBy): DamagedMedicine
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $batch = MedicineBatch::findOrFail($data['medicine_batch_id']);
            $qty = (int) $data['qty'];

            if ($qty > $batch->remaining_qty) {
                throw new InsufficientStockException(
                    "Only {$batch->remaining_qty} units remain in batch {$batch->batch_no}."
                );
            }

            $damaged = DamagedMedicine::create([
                'medicine_batch_id' => $batch->id,
                'qty' => $qty,
                'reason' => $data['reason'] ?? null,
                'created_by' => $createdBy,
            ]);

            $batch->decrement('remaining_qty', $qty);

            $this->stockMovementService->record(
                medicineId: $batch->medicine_id,
                batchId: $batch->id,
                type: StockMovement::TYPE_DAMAGE,
                qty: -$qty,
                reference: 'damaged_medicine',
                referenceId: $damaged->id,
            );

            return $damaged;
        });
    }
}
