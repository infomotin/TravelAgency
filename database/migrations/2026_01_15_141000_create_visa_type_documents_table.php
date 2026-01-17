<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_type_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_type_id')->constrained('visa_types')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_type_documents');
    }
};
