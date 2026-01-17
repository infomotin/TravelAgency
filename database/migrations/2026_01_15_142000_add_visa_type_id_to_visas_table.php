<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->foreignId('visa_type_id')->nullable()->constrained('visa_types')->nullOnDelete()->after('visa_fee');
        });
    }

    public function down(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('visa_type_id');
        });
    }
};

