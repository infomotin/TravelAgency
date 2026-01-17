<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->string('type')->index();
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile', 50)->nullable();
            $table->boolean('sent_gift')->default(false)->index();
            $table->date('gift_sent_date')->nullable()->index();
            $table->string('last_gift_name')->nullable();
            $table->text('gift_dates')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};

