<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class InitialPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'Agencies View', 'slug' => 'agencies.view'],
            ['name' => 'Agencies Create', 'slug' => 'agencies.create'],
            ['name' => 'Agencies Update', 'slug' => 'agencies.update'],
            ['name' => 'Agencies Delete', 'slug' => 'agencies.delete'],
            ['name' => 'Employees View', 'slug' => 'employees.view'],
            ['name' => 'Employees Create', 'slug' => 'employees.create'],
            ['name' => 'Employees Update', 'slug' => 'employees.update'],
            ['name' => 'Employees Delete', 'slug' => 'employees.delete'],
            ['name' => 'HR Setup View', 'slug' => 'hr_setup.view'],
            ['name' => 'HR Setup Create', 'slug' => 'hr_setup.create'],
            ['name' => 'HR Setup Update', 'slug' => 'hr_setup.update'],
            ['name' => 'HR Setup Delete', 'slug' => 'hr_setup.delete'],
            ['name' => 'HR Reports View', 'slug' => 'hr_reports.view'],
            ['name' => 'Payroll View', 'slug' => 'payroll.view'],
            ['name' => 'Payroll Create', 'slug' => 'payroll.create'],
            ['name' => 'Payroll Update', 'slug' => 'payroll.update'],
            ['name' => 'Payroll Delete', 'slug' => 'payroll.delete'],
            ['name' => 'Security View', 'slug' => 'security.view'],
            ['name' => 'Security Create', 'slug' => 'security.create'],
            ['name' => 'Security Update', 'slug' => 'security.update'],
            ['name' => 'Security Delete', 'slug' => 'security.delete'],
            ['name' => 'Accounts View', 'slug' => 'accounts.view'],
            ['name' => 'Accounts Create', 'slug' => 'accounts.create'],
            ['name' => 'Accounts Update', 'slug' => 'accounts.update'],
            ['name' => 'Accounts Delete', 'slug' => 'accounts.delete'],
            ['name' => 'Transactions View', 'slug' => 'transactions.view'],
            ['name' => 'Transactions Create', 'slug' => 'transactions.create'],
            ['name' => 'Transactions Update', 'slug' => 'transactions.update'],
            ['name' => 'Transactions Delete', 'slug' => 'transactions.delete'],
            ['name' => 'Accounting Reports View', 'slug' => 'accounting_reports.view'],
        ];

        foreach ($permissions as $data) {
            Permission::firstOrCreate(
                ['slug' => $data['slug']],
                ['name' => $data['name']]
            );
        }
    }
}

