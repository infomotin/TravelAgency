<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserDemoSeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::first();

        if (! $agency) {
            return;
        }

        $adminRole = Role::where('slug', 'admin')->first();

        $usersData = [
            [
                'name' => 'Demo Manager',
                'email' => 'manager@travelagency.test',
                'status' => 'active',
                'password' => Hash::make('Password@123'),
                'meta' => ['demo' => true],
            ],
            [
                'name' => 'Demo Accountant',
                'email' => 'accountant@travelagency.test',
                'status' => 'active',
                'password' => Hash::make('Password@123'),
                'meta' => ['demo' => true],
            ],
            [
                'name' => 'Demo Staff',
                'email' => 'staff@travelagency.test',
                'status' => 'active',
                'password' => Hash::make('Password@123'),
                'meta' => ['demo' => true],
            ],
        ];

        foreach ($usersData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'agency_id' => $agency->id,
                    'name' => $data['name'],
                    'status' => $data['status'],
                    'password' => $data['password'],
                ]
            );

            if ($adminRole) {
                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }
        }
    }
}

