<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;

class StockTransferService
{
    public function __construct(private readonly StockMovementService $stockMovementService) {}

    /**
     * @param  array{
     *     from_location_id: int,
     *     to_location_id: int,
     *     transfer_date: string,
     *     note?: string,
     *     items: array<int, array{medicine_id:int, qty:int}>
     * }  $data
     */
    public function create(array $data, ?int $createdBy): StockTransfer
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $transfer = StockTransfer::create([
                'from_location_id' => $data['from_location_id'],
                'to_location_id' => $data['to_location_id'],
                'transfer_date' => $data['transfer_date'],
                'note' => $data['note'] ?? null,
                'created_by' => $createdBy,
            ]);

            foreach ($data['items'] as $item) {
                $medicineId = (int) $item['medicine_id'];
                $qtyRemaining = (int) $item['qty'];

                if ($qtyRemaining <= 0) {
                    continue;
                }

                $batches = MedicineBatch::where('medicine_id', $medicineId)
                    ->where('location_id', $data['from_location_id'])
                    ->where('remaining_qty', '>', 0)
                    ->orderBy('created_at')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $available = $batches->sum('remaining_qty');

                if ($available < $qtyRemaining) {
                    $medicine = Medicine::find($medicineId);
                    throw new InsufficientStockException(
                        "Insufficient stock for {$medicine->medicine_name} at the source location: requested {$qtyRemaining}, available {$available}."
                    );
                }

                $transfer->items()->create(['medicine_id' => $medicineId, 'qty' => $item['qty']]);

                foreach ($batches as $batch) {
                    if ($qtyRemaining <= 0) {
                        break;
                    }

                    $qtyFromBatch = min($qtyRemaining, $batch->remaining_qty);

                    $batch->decrement('remaining_qty', $qtyFromBatch);

                    $destinationBatch = MedicineBatch::create([
                        'medicine_id' => $medicineId,
                        'location_id' => $data['to_location_id'],
                        'batch_no' => $batch->batch_no,
                        'purchase_price' => $batch->purchase_price,
                        'sale_price' => $batch->sale_price,
                        'quantity' => $qtyFromBatch,
                        'remaining_qty' => $qtyFromBatch,
                        'expiry_date' => $batch->expiry_date,
                        'manufacture_date' => $batch->manufacture_date,
                        'supplier_id' => $batch->supplier_id,
                    ]);

                    $this->stockMovementService->record(
                        medicineId: $medicineId,
                        batchId: $batch->id,
                        type: StockMovement::TYPE_TRANSFER,
                        qty: -$qtyFromBatch,
                        locationId: $data['from_location_id'],
                        reference: 'stock_transfer',
                        referenceId: $transfer->id,
                    );

                    $this->stockMovementService->record(
                        medicineId: $medicineId,
                        batchId: $destinationBatch->id,
                        type: StockMovement::TYPE_TRANSFER,
                        qty: $qtyFromBatch,
                        locationId: $data['to_location_id'],
                        reference: 'stock_transfer',
                        referenceId: $transfer->id,
                    );

                    $qtyRemaining -= $qtyFromBatch;
                }
            }

            return $transfer->load('items.medicine');
        });
    }
}
