<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Agency;
use App\Models\Bill;
use App\Models\Employee;
use App\Models\Party;
use App\Models\User;
use Illuminate\Database\Seeder;

class BillDemoSeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::first();

        if (! $agency) {
            return;
        }

        $client = Party::firstOrCreate(
            [
                'agency_id' => $agency->id,
                'name' => 'Demo Other Invoice Client',
                'type' => 'customer',
            ],
            [
                'status' => 'active',
            ]
        );

        $employee = Employee::firstOrCreate(
            [
                'agency_id' => $agency->id,
                'employee_code' => 'EMP-DEMO-001',
            ],
            [
                'name' => 'Demo Sales Person',
                'status' => 'active',
                'joining_date' => now()->toDateString(),
            ]
        );

        $creator = User::where('agency_id', $agency->id)->first() ?: User::first();

        if (! $creator) {
            return;
        }

        $revenueAccount = Account::where('agency_id', $agency->id)
            ->where('code', '4001')
            ->first();

        for ($i = 1; $i <= 5; $i++) {
            $billDate = now()->subDays(5 - $i)->toDateString();

            $quantity = 1;
            $unitPrice = 1000 + ($i * 100);
            $amount = $quantity * $unitPrice;

            $billNo = 'BL-DEMO-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT);

            $bill = Bill::firstOrCreate(
                [
                    'agency_id' => $agency->id,
                    'bill_no' => $billNo,
                ],
                [
                    'bill_date' => $billDate,
                    'due_date' => $billDate,
                    'type' => 'sale',
                    'party_id' => $client->id,
                    'employee_id' => $employee->id,
                    'contact_name' => null,
                    'reference' => 'Demo Other Invoice '.$i,
                    'details' => [
                        'billing_pax_name' => 'Demo Client '.$i,
                        'billing_description' => 'Demo other invoice '.$i,
                    ],
                    'total_amount' => $amount,
                    'paid_amount' => 0,
                    'balance_amount' => $amount,
                    'status' => 'open',
                    'created_by' => $creator->id,
                ]
            );

            if ($bill->wasRecentlyCreated && $revenueAccount) {
                $bill->lines()->create([
                    'account_id' => $revenueAccount->id,
                    'description' => 'Demo service '.$i,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                ]);
            }
        }
    }
}

