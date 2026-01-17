<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iata_code', 3)->unique();
            $table->string('country')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('airline_commission_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('airline_id')->constrained('airlines')->cascadeOnDelete();
            $table->enum('type', ['percentage', 'flat'])->default('percentage');
            $table->decimal('value', 12, 4);
            $table->decimal('min_fare', 12, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('airline_id')->constrained('airlines')->cascadeOnDelete();
            $table->string('ticket_no')->unique();
            $table->string('passenger_name');
            $table->decimal('fare', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->decimal('agent_commission_amount', 12, 2)->default(0);
            $table->decimal('profit_loss', 12, 2)->default(0);
            $table->date('issue_date')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('airline_commission_rules');
        Schema::dropIfExists('airlines');
    }
};
