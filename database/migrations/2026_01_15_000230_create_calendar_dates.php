<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendar_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->date('date')->index();
            $table->enum('status', ['WD', 'HD', 'GHD', 'OHD'])->default('WD')->index();
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['agency_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_dates');
    }
};

