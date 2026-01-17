<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserDemoSeeder::class,
            HajjDemoSeeder::class,
            TicketDemoSeeder::class,
            PassportDemoSeeder::class,
            VisaDemoSeeder::class,
            BillDemoSeeder::class,
        ]);
    }
}
