<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete()->after('passport_id');
            $table->decimal('visa_fee', 12, 2)->default(0)->after('expiry_date');
            $table->foreignId('agent_id')->nullable()->constrained('local_agents')->nullOnDelete()->after('visa_fee');
            $table->decimal('agent_commission', 12, 2)->default(0)->after('agent_id');
        });
    }

    public function down(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('country_id');
            $table->dropConstrainedForeignId('agent_id');
            $table->dropColumn(['visa_fee', 'agent_commission']);
        });
    }
};

