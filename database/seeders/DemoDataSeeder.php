<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            HajjDemoSeeder::class,
            TicketDemoSeeder::class,
            PassportDemoSeeder::class,
            VisaDemoSeeder::class,
            BillDemoSeeder::class,
        ]);
    }
}

