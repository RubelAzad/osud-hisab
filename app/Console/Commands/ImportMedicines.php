<?php

namespace App\Console\Commands;

use App\Imports\MedicinesImport;
use App\Models\Pharmacy;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportMedicines extends Command
{
    protected $signature = 'medicines:import {pharmacy_id : Pharmacy ID to import into} {file : Path to the Excel/CSV file}';

    protected $description = 'Bulk-import medicines for a pharmacy from an Excel/CSV file — recommended over the web UI for large files (thousands of rows), since it has no HTTP request timeout.';

    public function handle(): int
    {
        $pharmacy = Pharmacy::find($this->argument('pharmacy_id'));

        if (! $pharmacy) {
            $this->error('Pharmacy not found.');

            return self::FAILURE;
        }

        $path = $this->argument('file');

        if (! is_file($path)) {
            $this->error("File not found: {$path}");

            return self::FAILURE;
        }

        $this->info("Importing into: {$pharmacy->name}...");

        $import = new MedicinesImport($pharmacy->id);

        runForPharmacy($pharmacy, function () use ($import, $path) {
            Excel::import($import, $path);
        });

        $this->info("Imported: {$import->imported}");
        $this->info("Skipped: {$import->skipped}");

        return self::SUCCESS;
    }
}
