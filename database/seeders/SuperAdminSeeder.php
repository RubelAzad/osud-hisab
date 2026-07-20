<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@osudhisab.test'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'status' => true,
                'is_super_admin' => true,
                'pharmacy_id' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
