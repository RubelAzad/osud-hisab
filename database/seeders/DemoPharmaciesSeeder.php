<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\PharmacyOnboardingService;
use Illuminate\Database\Seeder;

class DemoPharmaciesSeeder extends Seeder
{
    /**
     * Seeds two separate demo pharmacies (tenants) so isolation between them is provable.
     */
    public function run(): void
    {
        $onboarding = app(PharmacyOnboardingService::class);

        if (User::where('email', 'admin@pharmacy-a.test')->doesntExist()) {
            $onboarding->register(
                ['name' => 'Green Life Pharmacy', 'owner_name' => 'Rahim Uddin', 'currency' => 'BDT', 'timezone' => 'Asia/Dhaka'],
                ['name' => 'Rahim Uddin', 'email' => 'admin@pharmacy-a.test', 'password' => 'password'],
            );
        }

        if (User::where('email', 'admin@pharmacy-b.test')->doesntExist()) {
            $onboarding->register(
                ['name' => 'City Care Pharmacy', 'owner_name' => 'Karim Ahmed', 'currency' => 'BDT', 'timezone' => 'Asia/Dhaka'],
                ['name' => 'Karim Ahmed', 'email' => 'admin@pharmacy-b.test', 'password' => 'password'],
            );
        }
    }
}
