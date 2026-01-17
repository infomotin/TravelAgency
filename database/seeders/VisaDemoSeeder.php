<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Passport;
use App\Models\Visa;
use App\Models\VisaType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VisaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $agency = Agency::first();

        if (! $agency) {
            return;
        }

        $passport = Passport::where('agency_id', $agency->id)->first();

        if (! $passport) {
            return;
        }

        $countryId = $passport->country_id ?: DB::table('countries')->first()?->id;

        $visaType = VisaType::firstOrCreate(
            [
                'country_id' => $countryId,
                'name' => 'Tourist Visa Demo',
            ],
            [
                'default_fee' => 5000,
                'status' => 'active',
            ]
        );

        $agentId = DB::table('local_agents')->where('agency_id', $agency->id)->first()?->id;

        for ($i = 1; $i <= 5; $i++) {
            $issue = now()->subMonths(2)->toDateString();
            $expiry = now()->addMonths(3)->toDateString();

            Visa::firstOrCreate(
                [
                    'passport_id' => $passport->id,
                    'visa_type_id' => $visaType->id,
                    'invoice_no' => 'VISA-DEMO-'.$i,
                ],
                [
                    'country_id' => $countryId,
                    'visa_type' => $visaType->name,
                    'issue_date' => $issue,
                    'expiry_date' => $expiry,
                    'visa_fee' => 5000,
                    'agent_id' => $agentId,
                    'agent_commission' => 0,
                ]
            );
        }
    }
}

