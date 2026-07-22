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
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MedicinesImport implements SkipsEmptyRows, ToModel, WithHeadingRow
{
    public int $imported = 0;

    public int $skipped = 0;

    public function model(array $row)
    {
        $categoryId = Category::where('name', $row['category'] ?? null)->value('id');
        $manufacturerId = Manufacturer::where('name', $row['manufacturer'] ?? null)->value('id');
        $genericId = Generic::where('name', $row['generic'] ?? null)->value('id');
        $medicineTypeId = MedicineType::where('name', $row['medicine_type'] ?? null)->value('id');
        $unitId = Unit::where('name', $row['unit'] ?? null)->value('id');

        if (! $categoryId || ! $manufacturerId || ! $genericId || ! $medicineTypeId || ! $unitId || empty($row['medicine_name'])) {
            $this->skipped++;

            return null;
        }

        // A duplicate barcode would otherwise crash the whole import on the unique constraint —
        // skip it gracefully instead (e.g. re-importing a file that includes already-imported rows).
        if (! empty($row['barcode']) && Medicine::where('barcode', $row['barcode'])->exists()) {
            $this->skipped++;

            return null;
        }

        $this->imported++;

        return new Medicine([
            'category_id' => $categoryId,
            'manufacturer_id' => $manufacturerId,
            'generic_id' => $genericId,
            'medicine_type_id' => $medicineTypeId,
            'unit_id' => $unitId,
            'medicine_name' => $row['medicine_name'],
            'strength' => $row['strength'] ?? null,
            'barcode' => $row['barcode'] ?? null,
            'purchase_price' => $row['purchase_price'] ?? 0,
            'sale_price' => $row['sale_price'] ?? 0,
            'minimum_stock' => $row['minimum_stock'] ?? 0,
            'vat' => $row['vat'] ?? 0,
            'status' => true,
        ]);
    }
}
