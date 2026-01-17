<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Airline;
use App\Models\Party;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketDemoSeeder extends Seeder
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
                'name' => 'Demo Ticket Client',
                'type' => 'customer',
            ],
            [
                'status' => 'active',
            ]
        );

        $airline = Airline::firstOrCreate(
            ['name' => 'Demo Airline'],
            ['iata_code' => 'DM', 'status' => 'active']
        );

        $vendorId = DB::table('ticket_agencies')->first()?->id;

        for ($i = 1; $i <= 5; $i++) {
            $issueDate = now()->subDays(5 - $i)->toDateString();

            $fare = 20000 + ($i * 1000);
            $baseFare = 18000 + ($i * 800);
            $tax = 2000;

            $clientPrice = $fare + $tax;
            $purchasePrice = $baseFare + $tax;
            $profit = $clientPrice - $purchasePrice;

            Ticket::firstOrCreate(
                [
                    'agency_id' => $agency->id,
                    'ticket_no' => 'TKT-DEMO-'.$i,
                ],
                [
                    'client_id' => $client->id,
                    'airline_id' => $airline->id,
                    'fare' => $fare,
                    'base_fare' => $baseFare,
                    'tax' => $tax,
                    'vendor_id' => $vendorId,
                    'issue_date' => $issueDate,
                    'passenger_name' => 'Demo Passenger '.$i,
                    'ticket_type' => 'oneway',
                    'client_price' => $clientPrice,
                    'purchase_price' => $purchasePrice,
                    'profit' => $profit,
                ]
            );
        }
    }
}

