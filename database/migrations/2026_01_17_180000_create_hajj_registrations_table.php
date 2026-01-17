<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hajj_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('parties')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('group_name')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('sales_date')->nullable();
            $table->date('due_date')->nullable();
            $table->foreignId('agent_id')->nullable()->constrained('local_agents')->nullOnDelete();

            $table->string('pilgrim_name')->nullable();
            $table->string('tracking_no')->nullable();
            $table->string('pre_reg_year')->nullable();
            $table->string('mobile')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nid')->nullable();
            $table->string('voucher_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('gender')->nullable();
            $table->string('maharam')->nullable();
            $table->string('possible_hajj_year')->nullable();

            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('pax_name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('profit', 12, 2)->default(0);
            $table->foreignId('vendor_id')->nullable()->constrained('ticket_agencies')->nullOnDelete();

            $table->decimal('sub_total', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('service_charge', 12, 2)->default(0);
            $table->decimal('vat_tax', 12, 2)->default(0);
            $table->decimal('net_total', 12, 2)->default(0);
            $table->decimal('agent_commission', 12, 2)->default(0);
            $table->decimal('invoice_due', 12, 2)->default(0);
            $table->decimal('present_balance', 12, 2)->default(0);
            $table->string('reference')->nullable();

            $table->string('payment_method')->nullable();
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->decimal('payment_amount', 12, 2)->default(0);
            $table->decimal('payment_discount', 12, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->string('receipt_no')->nullable();
            $table->text('payment_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hajj_registrations');
    }
};

