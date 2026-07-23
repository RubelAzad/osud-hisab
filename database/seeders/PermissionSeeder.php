<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    private const MODULES = [
        'dashboard', 'categories', 'manufacturers', 'generics', 'medicine_types',
        'units', 'medicines', 'suppliers', 'customers', 'purchases', 'sales',
        'sale_returns', 'purchase_returns', 'payments', 'expense_categories', 'expenses',
        'cash_accounts', 'damaged_medicines', 'notifications', 'settings', 'reports',
        'users', 'roles', 'locations', 'stock_transfers',
        'stock_adjustments', 'cheques', 'activity_logs', 'quotations', 'drafts',
    ];

    private const ACTIONS = ['view', 'create', 'edit', 'delete'];

    /**
     * Permissions are global definitions shared by every pharmacy (Spatie "teams" mode
     * scopes roles per-pharmacy, not permissions) — this only needs to run once.
     */
    public function run(): void
    {
        foreach (self::MODULES as $module) {
            foreach (self::ACTIONS as $action) {
                Permission::firstOrCreate(['name' => "{$module}.{$action}"]);
            }
        }
    }
}
