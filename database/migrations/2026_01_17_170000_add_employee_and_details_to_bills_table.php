<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->foreignId('employee_id')
                ->nullable()
                ->after('party_id')
                ->constrained('employees')
                ->nullOnDelete();

            $table->json('details')
                ->nullable()
                ->after('reference');
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropConstrainedForeignId('employee_id');
            $table->dropColumn('details');
        });
    }
};

