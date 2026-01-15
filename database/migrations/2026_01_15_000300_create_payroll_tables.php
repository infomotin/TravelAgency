<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete()->unique();
            $table->decimal('basic', 12, 2);
            $table->decimal('house_rent', 12, 2)->default(0);
            $table->decimal('medical', 12, 2)->default(0);
            $table->decimal('transport', 12, 2)->default(0);
            $table->decimal('overtime_rate_per_hour', 12, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('date')->index();
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('month', 7)->index();
            $table->decimal('gross', 12, 2);
            $table->decimal('deductions', 12, 2);
            $table->decimal('overtime_amount', 12, 2)->default(0);
            $table->decimal('net', 12, 2);
            $table->enum('status', ['draft', 'approved'])->default('draft')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['employee_id', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('advances');
        Schema::dropIfExists('salary_structures');
    }
};

