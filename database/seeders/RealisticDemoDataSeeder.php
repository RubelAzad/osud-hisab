<?php

namespace Database\Seeders;

use App\Models\CashAccount;
use App\Models\Category;
use App\Models\Customer;
use App\Models\ExpenseCategory;
use App\Models\Generic;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Pharmacy;
use App\Models\Supplier;
use App\Models\User;
use App\Support\PharmacyReferenceData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class RealisticDemoDataSeeder extends Seeder
{
    /**
     * Populates every existing pharmacy with realistic (real company / real generic /
     * real locale) reference and partner data. Deliberately does NOT touch Medicines —
     * that catalog is expected to come from a real product-list import, not fabricated data.
     */
    public function run(): void
    {
        Pharmacy::all()->each(function (Pharmacy $pharmacy) {
            runForPharmacy($pharmacy, function () use ($pharmacy) {
                app(PermissionRegistrar::class)->setPermissionsTeamId($pharmacy->id);

                foreach (PharmacyReferenceData::CATEGORIES as $name) {
                    Category::firstOrCreate(['name' => $name], ['status' => true]);
                }

                foreach (PharmacyReferenceData::MANUFACTURERS as $name) {
                    Manufacturer::firstOrCreate(['name' => $name], ['status' => true]);
                }

                foreach (PharmacyReferenceData::GENERICS as $name) {
                    Generic::firstOrCreate(['name' => $name]);
                }

                foreach (PharmacyReferenceData::EXPENSE_CATEGORIES as $name) {
                    ExpenseCategory::firstOrCreate(['name' => $name]);
                }

                foreach (PharmacyReferenceData::CASH_ACCOUNTS as $name) {
                    CashAccount::firstOrCreate(['account_name' => $name], ['balance' => 0]);
                }

                // "Main Branch" already exists from onboarding — add a few more real Dhaka-area branches.
                foreach (array_slice(PharmacyReferenceData::LOCATIONS, 0, 3) as $name) {
                    Location::firstOrCreate(['name' => $name], [
                        'address' => 'Dhaka, Bangladesh',
                        'status' => true,
                    ]);
                }

                if (Supplier::count() === 0) {
                    Supplier::factory()->count(8)->create();
                }

                if (Customer::count() === 0) {
                    Customer::factory()->count(40)->create();
                }

                $this->seedStaff($pharmacy);
            });
        });
    }

    private function seedStaff(Pharmacy $pharmacy): void
    {
        $mainLocation = Location::where('is_default', true)->first();

        $staff = [
            ['role' => 'Pharmacist', 'count' => 2],
            ['role' => 'Cashier', 'count' => 2],
        ];

        foreach ($staff as $group) {
            for ($i = 0; $i < $group['count']; $i++) {
                $user = User::factory()->create([
                    'pharmacy_id' => $pharmacy->id,
                    'location_id' => $mainLocation?->id,
                ]);

                // Derive the email from the name the factory actually generated, rather
                // than a separately-generated random name, so the two stay consistent.
                $email = Str::slug($user->name).'.'.Str::lower($group['role']).'@'.Str::slug($pharmacy->name).'.test';
                $suffix = 1;
                while (User::where('email', $email)->where('id', '!=', $user->id)->exists()) {
                    $email = Str::slug($user->name).'.'.Str::lower($group['role']).$suffix.'@'.Str::slug($pharmacy->name).'.test';
                    $suffix++;
                }

                $user->update(['email' => $email]);
                $user->assignRole($group['role']);
            }
        }
    }
}
