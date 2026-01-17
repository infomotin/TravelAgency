<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            if (! Schema::hasColumn('passports', 'passport_status_id')) {
                $table->unsignedBigInteger('passport_status_id')
                    ->nullable()
                    ->after('currency_id')
                    ->index()
                    ->comment('Links to passport_statuses');
            }
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            if (Schema::hasColumn('passports', 'passport_status_id')) {
                $table->dropColumn('passport_status_id');
            }
        });
    }
};
