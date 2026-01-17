<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialAgencyAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::firstOrCreate(
            ['slug' => 'default-agency'],
            [
                'code' => 'AG-001',
                'name' => 'Default Travel Agency',
                'currency' => 'USD',
                'status' => 'active',
            ]
        );

        $role = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'agency_id' => $agency->id,
            ]
        );

        $permissions = Permission::all();
        if ($permissions->isNotEmpty()) {
            $role->permissions()->syncWithoutDetaching($permissions->pluck('id')->all());
        }

        $user = User::firstOrCreate(
            ['email' => 'admin@travelagency.test'],
            [
                'agency_id' => $agency->id,
                'name' => 'System Administrator',
                'status' => 'active',
                'password' => Hash::make('Admin@12345'),
                'meta' => [
                    'is_seeded_admin' => true,
                ],
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}
