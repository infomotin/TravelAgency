<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->foreignId('local_agent_id')->nullable()->constrained('local_agents')->nullOnDelete()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('local_agent_id');
        });
    }
};

