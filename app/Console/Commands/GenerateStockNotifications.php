<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Pharmacy;
use App\Services\DashboardService;
use Illuminate\Console\Command;

class GenerateStockNotifications extends Command
{
    protected $signature = 'notifications:check-stock';

    protected $description = 'Refresh low-stock and expiring-soon notifications for every active pharmacy';

    public function handle(DashboardService $dashboardService): int
    {
        Pharmacy::where('status', true)->each(function (Pharmacy $pharmacy) use ($dashboardService) {
            runForPharmacy($pharmacy, function () use ($dashboardService, $pharmacy) {
                $outOfStock = $dashboardService->outOfStockMedicines(50)->map(fn ($medicine) => [
                    'title' => "Out of stock: {$medicine->medicine_name}",
                    'message' => "{$medicine->medicine_name} is now out of stock.",
                ]);

                $this->refresh($pharmacy, 'out_of_stock', $outOfStock->all());

                $lowStock = $dashboardService->lowStockMedicines(50)->map(fn ($medicine) => [
                    'title' => "Low stock: {$medicine->medicine_name}",
                    'message' => "Only {$medicine->total_stock} left (minimum {$medicine->minimum_stock}).",
                ]);

                $this->refresh($pharmacy, 'low_stock', $lowStock->all());

                $expiring = $dashboardService->expiringMedicineBatches(30, 50)->map(fn ($batch) => [
                    'title' => "Expiring soon: {$batch->medicine->medicine_name}",
                    'message' => "Batch {$batch->batch_no} expires on {$batch->expiry_date->format('Y-m-d')}.",
                ]);

                $this->refresh($pharmacy, 'expiring', $expiring->all());
            });
        });

        $this->info('Stock notifications refreshed.');

        return self::SUCCESS;
    }

    /**
     * @param  array<int, array{title: string, message: string}>  $items
     */
    private function refresh(Pharmacy $pharmacy, string $type, array $items): void
    {
        Notification::where('pharmacy_id', $pharmacy->id)
            ->where('type', $type)
            ->where('is_read', false)
            ->delete();

        foreach ($items as $item) {
            Notification::create([
                'pharmacy_id' => $pharmacy->id,
                'title' => $item['title'],
                'message' => $item['message'],
                'type' => $type,
            ]);
        }
    }
}
