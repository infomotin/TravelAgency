<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop old tables if they exist to start fresh
        Schema::dropIfExists('journal_lines');
        Schema::dropIfExists('journal_entries');
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('accounts');
        Schema::enableForeignKeyConstraints();

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('type'); // asset, liability, equity, income, expense
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->text('description')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['agency_id', 'code']);
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
            $table->string('voucher_no');
            $table->date('date');
            $table->string('type'); // payment, receipt, journal, contra
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->string('status')->default('approved'); // draft, approved, cancelled
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['agency_id', 'voucher_no']);
        });

        Schema::create('transaction_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_lines');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('accounts');
    }
};
