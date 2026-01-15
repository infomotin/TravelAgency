<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agency;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $agencies = Agency::all();

        foreach ($agencies as $agency) {
            $this->createChartOfAccounts($agency);
        }
    }

    private function createChartOfAccounts(Agency $agency)
    {
        $coa = [
            'Assets' => [
                'type' => 'asset',
                'code' => '1000',
                'children' => [
                    'Current Assets' => [
                        'code' => '1010',
                        'children' => [
                            'Cash in Hand' => ['code' => '1001', 'is_system' => true],
                            'Bank Accounts' => ['code' => '1002', 'is_system' => true],
                            'Accounts Receivable' => ['code' => '1003'],
                        ]
                    ],
                    'Fixed Assets' => [
                        'code' => '1020',
                        'children' => [
                            'Furniture & Fixtures' => ['code' => '1101'],
                            'Computers & Equipment' => ['code' => '1102'],
                        ]
                    ]
                ]
            ],
            'Liabilities' => [
                'type' => 'liability',
                'code' => '2000',
                'children' => [
                    'Current Liabilities' => [
                        'code' => '2010',
                        'children' => [
                            'Accounts Payable' => ['code' => '2001'],
                            'Tax Payable' => ['code' => '2002'],
                        ]
                    ]
                ]
            ],
            'Equity' => [
                'type' => 'equity',
                'code' => '3000',
                'children' => [
                    'Capital' => ['code' => '3001'],
                    'Retained Earnings' => ['code' => '3002'],
                ]
            ],
            'Income' => [
                'type' => 'income',
                'code' => '4000',
                'children' => [
                    'Operating Income' => [
                        'code' => '4010',
                        'children' => [
                            'Ticket Sales' => ['code' => '4001'],
                            'Visa Services' => ['code' => '4002'],
                            'Hotel Booking' => ['code' => '4003'],
                            'Commission Income' => ['code' => '4004'],
                        ]
                    ]
                ]
            ],
            'Expenses' => [
                'type' => 'expense',
                'code' => '5000',
                'children' => [
                    'Operating Expenses' => [
                        'code' => '5010',
                        'children' => [
                            'Rent Expense' => ['code' => '5001'],
                            'Salaries & Wages' => ['code' => '5002'],
                            'Electricity & Water' => ['code' => '5003'],
                            'Internet & Telephone' => ['code' => '5004'],
                            'Office Supplies' => ['code' => '5005'],
                        ]
                    ]
                ]
            ],
        ];

        foreach ($coa as $name => $data) {
            $this->createAccount($agency->id, $name, $data['type'], null, $data);
        }
    }

    private function createAccount($agencyId, $name, $type, $parentId, $data)
    {
        $code = $data['code'];

        $account = Account::firstOrCreate(
            ['agency_id' => $agencyId, 'code' => $code],
            [
                'name' => $name,
                'type' => $type,
                'parent_id' => $parentId,
                'is_system' => $data['is_system'] ?? false,
            ]
        );

        if (isset($data['children'])) {
            foreach ($data['children'] as $childName => $childData) {
                // Inherit type from parent if not specified
                $childType = $childData['type'] ?? $type;
                $this->createAccount($agencyId, $childName, $childType, $account->id, $childData);
            }
        }
    }
}
