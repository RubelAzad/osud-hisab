<?php

namespace App\Imports;

use App\Models\Location;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Bulk-creates opening MedicineBatch rows for medicines that already exist in the catalog —
 * used to seed real initial stock quantities, not to create medicines themselves (see
 * MedicinesImport for that). Same batch-insert shape as MedicinesImport since pharmacy_id
 * must be set explicitly here too (WithBatchInserts bypasses the creating event).
 */
class OpeningStockImport implements SkipsEmptyRows, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    public int $imported = 0;

    public int $skipped = 0;

    private readonly int $pharmacyId;

    private array $medicinesByBarcode;

    private array $medicinesByName;

    private array $locations;

    public function __construct(?int $pharmacyId = null)
    {
        $this->pharmacyId = $pharmacyId ?? currentPharmacyId();

        $this->medicinesByBarcode = Medicine::whereNotNull('barcode')->pluck('id', 'barcode')->all();
        $this->medicinesByName = Medicine::pluck('id', 'medicine_name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim($name)) => $id])
            ->all();
        $this->locations = Location::pluck('id', 'name')
            ->mapWithKeys(fn ($id, $name) => [mb_strtolower(trim($name)) => $id])
            ->all();
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function model(array $row)
    {
        $identifier = trim((string) ($row['barcode_or_medicine_name'] ?? ''));
        $medicineId = $this->medicinesByBarcode[$identifier] ?? $this->medicinesByName[mb_strtolower($identifier)] ?? null;
        $locationId = $this->locations[mb_strtolower(trim($row['location'] ?? ''))] ?? null;
        $qty = (int) ($row['quantity'] ?? 0);

        if (! $medicineId || ! $locationId || $qty < 1) {
            $this->skipped++;

            return null;
        }

        $this->imported++;

        return new MedicineBatch([
            'pharmacy_id' => $this->pharmacyId,
            'location_id' => $locationId,
            'medicine_id' => $medicineId,
            'batch_no' => trim((string) ($row['batch_no'] ?? '')) ?: ('OB-'.now()->format('YmdHis').'-'.$this->imported),
            'purchase_price' => $row['purchase_price'] ?? 0,
            'sale_price' => $row['sale_price'] ?? 0,
            'quantity' => $qty,
            'remaining_qty' => $qty,
            'expiry_date' => $row['expiry_date'] ?? null,
        ]);
    }
}
