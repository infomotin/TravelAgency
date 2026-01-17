<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_categories')) {
            Schema::create('client_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agency_id')->index();
                $table->string('name');
                $table->string('prefix', 20)->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active')->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('client_categories');
    }
};
