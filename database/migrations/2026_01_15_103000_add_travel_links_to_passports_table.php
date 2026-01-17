<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete()->after('address');
            $table->foreignId('airport_id')->nullable()->constrained('airports')->nullOnDelete()->after('country_id');
            $table->foreignId('airline_id')->nullable()->constrained('airlines')->nullOnDelete()->after('airport_id');
            $table->foreignId('ticket_agency_id')->nullable()->constrained('ticket_agencies')->nullOnDelete()->after('airline_id');
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete()->after('ticket_agency_id');
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('currency_id');
            $table->dropConstrainedForeignId('ticket_agency_id');
            $table->dropConstrainedForeignId('airline_id');
            $table->dropConstrainedForeignId('airport_id');
            $table->dropConstrainedForeignId('country_id');
        });
    }
};
