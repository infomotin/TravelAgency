<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->constrained('parties')->nullOnDelete()->after('agency_id');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete()->after('client_id');
            $table->string('invoice_no')->nullable()->after('profit_loss');
            $table->date('sales_date')->nullable()->after('invoice_no');
            $table->date('due_date')->nullable()->after('sales_date');
            $table->foreignId('agent_id')->nullable()->constrained('local_agents')->nullOnDelete()->after('branch_id');
            $table->foreignId('vendor_id')->nullable()->constrained('ticket_agencies')->nullOnDelete()->after('tax');
            $table->decimal('base_fare', 12, 2)->default(0)->after('fare');
            $table->decimal('commission_percent', 8, 4)->default(0)->after('vendor_id');
            $table->decimal('taxes_commission', 12, 2)->default(0)->after('commission_amount');
            $table->decimal('ait', 12, 2)->default(0)->after('taxes_commission');
            $table->decimal('net_commission', 12, 2)->default(0)->after('ait');
            $table->foreignId('from_airport_id')->nullable()->constrained('airports')->nullOnDelete()->after('airline_id');
            $table->foreignId('to_airport_id')->nullable()->constrained('airports')->nullOnDelete()->after('from_airport_id');
            $table->string('pnr')->nullable()->after('ticket_no');
            $table->string('gds')->nullable()->after('pnr');
            $table->decimal('discount', 12, 2)->default(0)->after('net_commission');
            $table->decimal('extra_fee', 12, 2)->default(0)->after('discount');
            $table->string('class')->nullable()->after('extra_fee');
            $table->string('ticket_type')->nullable()->after('class');
            $table->unsignedInteger('segment')->default(1)->after('ticket_type');
            $table->date('journey_date')->nullable()->after('issue_date');
            $table->date('return_date')->nullable()->after('journey_date');
            $table->text('remarks')->nullable()->after('return_date');
            $table->decimal('tax_amount', 12, 2)->default(0)->after('remarks');
            $table->decimal('commission_7_percent', 12, 2)->default(0)->after('tax_amount');
            $table->decimal('client_price', 12, 2)->default(0)->after('commission_7_percent');
            $table->decimal('purchase_price', 12, 2)->default(0)->after('client_price');
            $table->decimal('profit', 12, 2)->default(0)->after('purchase_price');
            $table->decimal('country_tax_bd', 12, 2)->default(0)->after('profit');
            $table->decimal('country_tax_ut', 12, 2)->default(0)->after('country_tax_bd');
            $table->decimal('country_tax_e5', 12, 2)->default(0)->after('country_tax_ut');
            $table->decimal('country_tax_es', 12, 2)->default(0)->after('country_tax_e5');
            $table->decimal('country_tax_xt', 12, 2)->default(0)->after('country_tax_es');
            $table->decimal('country_tax_ow', 12, 2)->default(0)->after('country_tax_xt');
            $table->decimal('country_tax_qa', 12, 2)->default(0)->after('country_tax_ow');
            $table->decimal('country_tax_pz', 12, 2)->default(0)->after('country_tax_qa');
            $table->decimal('country_tax_g4', 12, 2)->default(0)->after('country_tax_pz');
            $table->decimal('country_tax_p7', 12, 2)->default(0)->after('country_tax_g4');
            $table->decimal('country_tax_p8', 12, 2)->default(0)->after('country_tax_p7');
            $table->decimal('country_tax_r9', 12, 2)->default(0)->after('country_tax_p8');
            $table->foreignId('passport_id')->nullable()->constrained('passports')->nullOnDelete()->after('passenger_name');
            $table->string('pax_type')->nullable()->after('passport_id');
            $table->string('contact_no')->nullable()->after('pax_type');
            $table->string('email')->nullable()->after('contact_no');
            $table->date('date_of_birth')->nullable()->after('email');
            $table->date('passport_issue_date')->nullable()->after('date_of_birth');
            $table->date('passport_expire_date')->nullable()->after('passport_issue_date');
            $table->string('flight_from')->nullable()->after('country_tax_r9');
            $table->string('flight_to')->nullable()->after('flight_from');
            $table->string('flight_airline')->nullable()->after('flight_to');
            $table->string('flight_no')->nullable()->after('flight_airline');
            $table->date('flight_date')->nullable()->after('flight_no');
            $table->time('departure_time')->nullable()->after('flight_date');
            $table->time('arrival_time')->nullable()->after('departure_time');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('client_id');
            $table->dropConstrainedForeignId('employee_id');
            $table->dropConstrainedForeignId('agent_id');
            $table->dropConstrainedForeignId('vendor_id');
            $table->dropConstrainedForeignId('from_airport_id');
            $table->dropConstrainedForeignId('to_airport_id');
            $table->dropConstrainedForeignId('passport_id');
            $table->dropColumn([
                'invoice_no',
                'sales_date',
                'due_date',
                'base_fare',
                'commission_percent',
                'taxes_commission',
                'ait',
                'net_commission',
                'pnr',
                'gds',
                'discount',
                'extra_fee',
                'class',
                'ticket_type',
                'segment',
                'journey_date',
                'return_date',
                'remarks',
                'tax_amount',
                'commission_7_percent',
                'client_price',
                'purchase_price',
                'profit',
                'country_tax_bd',
                'country_tax_ut',
                'country_tax_e5',
                'country_tax_es',
                'country_tax_xt',
                'country_tax_ow',
                'country_tax_qa',
                'country_tax_pz',
                'country_tax_g4',
                'country_tax_p7',
                'country_tax_p8',
                'country_tax_r9',
                'pax_type',
                'contact_no',
                'email',
                'date_of_birth',
                'passport_issue_date',
                'passport_expire_date',
                'flight_from',
                'flight_to',
                'flight_airline',
                'flight_no',
                'flight_date',
                'departure_time',
                'arrival_time',
            ]);
        });
    }
};

