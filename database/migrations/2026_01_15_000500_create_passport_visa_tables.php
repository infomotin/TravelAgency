<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->string('holder_name');
            $table->string('passport_no')->unique();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->index();
            $table->string('document_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('visas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passport_id')->constrained('passports')->cascadeOnDelete();
            $table->string('visa_type');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->index();
            $table->string('document_path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visas');
        Schema::dropIfExists('passports');
    }
};
