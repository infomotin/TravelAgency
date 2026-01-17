<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('transport_types')) {
            Schema::create('transport_types', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agency_id')->index();
                $table->string('name');
                $table->enum('status', ['active', 'inactive'])->default('active')->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_types');
    }
};
