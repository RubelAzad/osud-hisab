<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Generic;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\MedicineType;
use App\Models\Unit;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Built for real product-list scale (thousands of rows): lookup tables are preloaded once
 * instead of queried per row, and rows are read/inserted in chunks rather than one at a time.
 * WithBatchInserts uses a raw bulk insert that bypasses Eloquent's "creating" event, so
 * pharmacy_id is set explicitly here rather than relying on the BelongsToPharmacy trait.
 */
class MedicinesImport implements SkipsEmptyRows, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    public int $imported = 0;

    public int $skipped = 0;

    private readonly int $pharmacyId;

    private array $categories;

    private array $manufacturers;

    private array $generics;

    private array $medicineTypes;

    private array $units;

    private array $existingBarcodes;

    private array $seenBarcodes = [];

    public function __construct(?int $pharmacyId = null)
    {
        $this->pharmacyId = $pharmacyId ?? currentPharmacyId();

        $this->categories = $this->lookupMap(Category::class);
        $this->manufacturers = $this->lookupMap(Manufacturer::class);
        $this->generics = $this->lookupMap(Generic::class);
        $this->medicineTypes = $this->lookupMap(MedicineType::class);
        $this->units = $this->lookupMap(Unit::class);
        $this->existingBarcodes = Medicine::whereNotNull('barcode')->pluck('barcode')->flip()->all();
    }

    private function lookupMap(string $modelClass): array
    {
        return $modelClass::pluck('id', 'name')
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
        $categoryId = $this->categories[mb_strtolower(trim($row['category'] ?? ''))] ?? null;
        $manufacturerId = $this->manufacturers[mb_strtolower(trim($row['manufacturer'] ?? ''))] ?? null;
        $genericId = $this->generics[mb_strtolower(trim($row['generic'] ?? ''))] ?? null;
        $medicineTypeId = $this->medicineTypes[mb_strtolower(trim($row['medicine_type'] ?? ''))] ?? null;
        $unitId = $this->units[mb_strtolower(trim($row['unit'] ?? ''))] ?? null;

        if (! $categoryId || ! $manufacturerId || ! $genericId || ! $medicineTypeId || ! $unitId || empty($row['medicine_name'])) {
            $this->skipped++;

            return null;
        }

        $barcode = trim((string) ($row['barcode'] ?? '')) ?: null;

        // A duplicate barcode (already in the DB, or repeated earlier in this same file)
        // would otherwise crash the whole import on the unique constraint — skip it instead.
        if ($barcode && (isset($this->existingBarcodes[$barcode]) || isset($this->seenBarcodes[$barcode]))) {
            $this->skipped++;

            return null;
        }

        if ($barcode) {
            $this->seenBarcodes[$barcode] = true;
        }

        $this->imported++;

        return new Medicine([
            'pharmacy_id' => $this->pharmacyId,
            'category_id' => $categoryId,
            'manufacturer_id' => $manufacturerId,
            'generic_id' => $genericId,
            'medicine_type_id' => $medicineTypeId,
            'unit_id' => $unitId,
            'medicine_name' => $row['medicine_name'],
            'strength' => $row['strength'] ?? null,
            'barcode' => $barcode,
            'purchase_price' => $row['purchase_price'] ?? 0,
            'sale_price' => $row['sale_price'] ?? 0,
            'minimum_stock' => $row['minimum_stock'] ?? 0,
            'vat' => $row['vat'] ?? 0,
            'status' => true,
        ]);
    }
}
