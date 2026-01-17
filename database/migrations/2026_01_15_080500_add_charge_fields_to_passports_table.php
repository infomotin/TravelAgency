<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->decimal('entry_charge', 12, 2)->default(0)->after('document_path');
            $table->decimal('person_commission', 12, 2)->default(0)->after('entry_charge');
            $table->boolean('is_free')->default(false)->after('person_commission');
        });
    }

    public function down(): void
    {
        Schema::table('passports', function (Blueprint $table) {
            $table->dropColumn(['entry_charge', 'person_commission', 'is_free']);
        });
    }
};
