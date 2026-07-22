<?php

namespace App\Services;

use App\Models\StockMovement;

class StockMovementService
{
    public function record(int $medicineId, ?int $batchId, string $type, int $qty, int $locationId, ?string $reference = null, ?int $referenceId = null): StockMovement
    {
        return StockMovement::create([
            'medicine_id' => $medicineId,
            'batch_id' => $batchId,
            'location_id' => $locationId,
            'type' => $type,
            'qty' => $qty,
            'reference' => $reference,
            'reference_id' => $referenceId,
        ]);
    }
}
