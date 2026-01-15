<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->string('mobile')->nullable()->after('holder_name');
            $table->string('address')->nullable()->after('mobile');
            $table->string('purpose')->nullable()->after('is_free');
            $table->string('local_agent_name')->nullable()->after('purpose');
            $table->enum('local_agent_commission_type', ['percentage', 'fixed'])->nullable()->after('local_agent_name');
            $table->decimal('local_agent_commission_value', 12, 2)->default(0)->after('local_agent_commission_type');
            $table->decimal('local_agent_commission_amount', 12, 2)->default(0)->after('local_agent_commission_value');
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->dropColumn([
                'mobile',
                'address',
                'purpose',
                'local_agent_name',
                'local_agent_commission_type',
                'local_agent_commission_value',
                'local_agent_commission_amount',
            ]);
        });
    }
};

