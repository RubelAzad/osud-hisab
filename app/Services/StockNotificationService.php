<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\Notification;
use App\Models\Pharmacy;

class StockNotificationService
{
    public function checkAndNotify(Medicine $medicine): void
    {
        $medicine->loadSum('batches as total_stock', 'remaining_qty');
        $totalStock = (int) ($medicine->total_stock ?? 0);

        if ($totalStock <= 0) {
            $this->notify($medicine, 'out_of_stock', "Out of stock", "{$medicine->medicine_name} is now out of stock.");
        } elseif ($totalStock <= $medicine->minimum_stock) {
            $this->notify($medicine, 'low_stock', "Low stock: {$medicine->medicine_name}", "Only {$totalStock} left (minimum {$medicine->minimum_stock}).");
        }
    }

    private function notify(Medicine $medicine, string $type, string $title, string $message): void
    {
        $pharmacyId = $medicine->pharmacy_id ?? currentPharmacyId();

        $exists = Notification::where('pharmacy_id', $pharmacyId)
            ->where('type', $type)
            ->where('is_read', false)
            ->where('title', $title)
            ->exists();

        if (! $exists) {
            Notification::create([
                'pharmacy_id' => $pharmacyId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
            ]);
        }
    }
}
