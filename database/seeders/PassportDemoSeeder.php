<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Passport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportDemoSeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::first();

        if (! $agency) {
            return;
        }

        $countryId = DB::table('countries')->first()?->id;
        $airportId = DB::table('airports')->first()?->id;
        $airlineId = DB::table('airlines')->first()?->id;
        $ticketAgencyId = DB::table('ticket_agencies')->first()?->id;
        $currencyId = DB::table('currencies')->first()?->id;
        $localAgentId = DB::table('local_agents')->where('agency_id', $agency->id)->first()?->id;
        $passportStatusId = DB::table('passport_statuses')->where('agency_id', $agency->id)->first()?->id;

        for ($i = 1; $i <= 5; $i++) {
            $issue = now()->subYears(3)->toDateString();
            $expiry = now()->addYears(2)->toDateString();

            Passport::firstOrCreate(
                [
                    'agency_id' => $agency->id,
                    'passport_no' => 'P-DEMO-'.$i,
                ],
                [
                    'holder_name' => 'Demo Holder '.$i,
                    'mobile' => '01710000'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'address' => 'Demo address '.$i,
                    'local_agent_id' => $localAgentId,
                    'country_id' => $countryId,
                    'airport_id' => $airportId,
                    'airline_id' => $airlineId,
                    'ticket_agency_id' => $ticketAgencyId,
                    'currency_id' => $currencyId,
                    'issue_date' => $issue,
                    'expiry_date' => $expiry,
                    'entry_charge' => 1500,
                    'invoice_no' => 'PP-DEMO-'.$i,
                    'invoice_date' => now()->toDateString(),
                    'person_commission' => 0,
                    'is_free' => false,
                    'purpose' => 'visa',
                    'passport_status_id' => $passportStatusId,
                ]
            );
        }
    }
}

