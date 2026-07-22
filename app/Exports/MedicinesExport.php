<?php

namespace App\Exports;

use App\Models\Medicine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MedicinesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Medicine::with(['category', 'manufacturer', 'generic', 'medicineType', 'unit'])
            ->orderBy('medicine_name')
            ->get()
            ->map(fn (Medicine $m) => [
                $m->medicine_name,
                $m->strength,
                $m->barcode,
                $m->category->name ?? '',
                $m->manufacturer->name ?? '',
                $m->generic->name ?? '',
                $m->medicineType->name ?? '',
                $m->unit->name ?? '',
                $m->purchase_price,
                $m->sale_price,
                $m->minimum_stock,
                $m->vat,
            ]);
    }

    public function headings(): array
    {
        return [
            'Medicine Name', 'Strength', 'Barcode', 'Category', 'Manufacturer',
            'Generic', 'Medicine Type', 'Unit', 'Purchase Price', 'Sale Price',
            'Minimum Stock', 'VAT',
        ];
    }
}
