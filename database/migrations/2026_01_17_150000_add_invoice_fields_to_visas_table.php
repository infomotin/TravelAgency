<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->string('invoice_no')->nullable()->after('agent_commission');
            $table->date('invoice_date')->nullable()->after('invoice_no');
        });
    }

    public function down(): void
    {
        Schema::table('visas', function (Blueprint $table) {
            $table->dropColumn(['invoice_no', 'invoice_date']);
        });
    }
};
