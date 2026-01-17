<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Employee;
use App\Models\HajjRegistration;
use App\Models\Party;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HajjDemoSeeder extends Seeder
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
                'name' => 'Demo Hajj Client',
                'type' => 'customer',
            ],
            [
                'email' => 'hajj-client@example.com',
                'phone' => '01700000000',
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

        $product = Product::firstOrCreate(
            [
                'agency_id' => $agency->id,
                'name' => 'Hajj Package Demo',
            ],
            [
                'status' => 'active',
                'category' => 'HAJJ',
            ]
        );

        $agentId = DB::table('local_agents')->first()?->id;
        $vendorId = DB::table('ticket_agencies')->first()?->id;

        $cashAccount = DB::table('accounts')
            ->where('agency_id', $agency->id)
            ->where('code', '1001')
            ->first();

        for ($i = 1; $i <= 10; $i++) {
            $salesDate = now()->subDays(10 - $i)->toDateString();

            $quantity = 1;
            $unitPrice = 30000 + ($i * 500);
            $costPrice = 25000 + ($i * 400);

            $totalSales = $quantity * $unitPrice;
            $totalCost = $quantity * $costPrice;
            $profit = $totalSales - $totalCost;

            $discount = 0;
            $serviceCharge = 0;
            $vatTax = 0;

            $subTotal = $totalSales;
            $netTotal = $subTotal - $discount + $serviceCharge + $vatTax;

            $paymentAmount = $netTotal;
            $paymentDiscount = 0;

            $invoiceDue = $netTotal - $paymentAmount - $paymentDiscount;

            $invoiceNo = 'HAJ-DEMO-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT);

            HajjRegistration::firstOrCreate(
                [
                    'agency_id' => $agency->id,
                    'invoice_no' => $invoiceNo,
                ],
                [
                    'client_id' => $client->id,
                    'employee_id' => $employee->id,
                    'group_name' => 'Demo Group',
                    'sales_date' => $salesDate,
                    'due_date' => $salesDate,
                    'agent_id' => $agentId,
                    'pilgrim_name' => 'Demo Pilgrim '.$i,
                    'tracking_no' => 'TRK'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'pre_reg_year' => now()->year,
                    'mobile' => '01700000'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'nid' => '1234567890'.$i,
                    'voucher_no' => 'VCH'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'serial_no' => 'SRL'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'gender' => $i % 2 === 0 ? 'male' : 'female',
                    'possible_hajj_year' => (string) now()->year,
                    'product_id' => $product->id,
                    'pax_name' => 'Pax '.$i,
                    'description' => 'Demo Hajj package registration '.$i,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'cost_price' => $costPrice,
                    'total_sales' => $totalSales,
                    'total_cost' => $totalCost,
                    'profit' => $profit,
                    'vendor_id' => $vendorId,
                    'sub_total' => $subTotal,
                    'discount' => $discount,
                    'service_charge' => $serviceCharge,
                    'vat_tax' => $vatTax,
                    'net_total' => $netTotal,
                    'agent_commission' => 0,
                    'invoice_due' => $invoiceDue,
                    'present_balance' => $invoiceDue,
                    'reference' => 'Demo data '.$i,
                    'payment_method' => 'Cash',
                    'account_id' => $cashAccount->id ?? null,
                    'payment_amount' => $paymentAmount,
                    'payment_discount' => $paymentDiscount,
                    'payment_date' => $salesDate,
                    'receipt_no' => 'MR-DEMO-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'payment_note' => 'Demo payment '.$i,
                ]
            );
        }
    }
}
