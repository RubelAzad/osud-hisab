<?php

namespace App\Services;

use App\Models\CashAccount;
use App\Models\Location;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PharmacyOnboardingService
{
    private const ROLE_PERMISSIONS = [
        'Pharmacist' => [
            'dashboard.view',
            'medicines.view', 'medicines.create', 'medicines.edit',
            'categories.view', 'categories.create', 'categories.edit',
            'manufacturers.view', 'manufacturers.create', 'manufacturers.edit',
            'generics.view', 'generics.create', 'generics.edit',
            'medicine_types.view', 'medicine_types.create', 'medicine_types.edit',
            'units.view', 'units.create', 'units.edit',
            'suppliers.view', 'suppliers.create', 'suppliers.edit',
            'customers.view', 'customers.create', 'customers.edit',
            'purchases.view', 'purchases.create', 'purchases.edit',
            'sales.view', 'sales.create', 'sales.edit',
            'sale_returns.view', 'sale_returns.create',
            'quotations.view', 'quotations.create',
            'drafts.view', 'drafts.create',
            'purchase_returns.view', 'purchase_returns.create',
            'payments.view', 'payments.create',
            'expense_categories.view', 'expenses.view', 'expenses.create',
            'cash_accounts.view',
            'damaged_medicines.view', 'damaged_medicines.create',
            'stock_adjustments.view', 'stock_adjustments.create',
            'notifications.view',
            'reports.view',
            'locations.view',
            'stock_transfers.view', 'stock_transfers.create',
        ],
        'Cashier' => [
            'dashboard.view',
            'medicines.view',
            'customers.view', 'customers.create',
            'sales.view', 'sales.create',
            'sale_returns.view', 'sale_returns.create',
            'drafts.view', 'drafts.create',
            'payments.view', 'payments.create',
            'notifications.view',
        ],
    ];

    /**
     * Create a new pharmacy (tenant), its standard team-scoped roles, and its owner Admin user.
     *
     * @param  array<string, mixed>  $pharmacyData
     * @param  array<string, mixed>  $ownerData
     */
    public function register(array $pharmacyData, array $ownerData): User
    {
        return DB::transaction(function () use ($pharmacyData, $ownerData) {
            $pharmacy = Pharmacy::create(array_merge($pharmacyData, ['status' => true]));

            app(PermissionRegistrar::class)->setPermissionsTeamId($pharmacy->id);

            $adminRole = Role::create(['name' => 'Admin', 'pharmacy_id' => $pharmacy->id]);
            $adminRole->syncPermissions(Permission::all());

            foreach (self::ROLE_PERMISSIONS as $roleName => $permissions) {
                $role = Role::create(['name' => $roleName, 'pharmacy_id' => $pharmacy->id]);
                $role->syncPermissions($permissions);
            }

            $location = Location::create([
                'pharmacy_id' => $pharmacy->id,
                'name' => 'Main Branch',
                'is_default' => true,
                'status' => true,
            ]);

            $owner = User::create(array_merge($ownerData, [
                'pharmacy_id' => $pharmacy->id,
                'location_id' => $location->id,
                'status' => true,
                'email_verified_at' => now(),
            ]));

            $owner->assignRole($adminRole);

            CashAccount::create([
                'pharmacy_id' => $pharmacy->id,
                'account_name' => 'Cash',
                'balance' => 0,
            ]);

            return $owner;
        });
    }
}
