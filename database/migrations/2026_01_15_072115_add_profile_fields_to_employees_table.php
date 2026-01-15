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
        Schema::table('employees', function (Blueprint $table) {
            // Personal Information
            $table->string('father_name')->nullable()->after('name');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->date('dob')->nullable()->after('mother_name');
            $table->string('gender')->nullable()->after('dob'); // Male, Female, Other
            $table->string('marital_status')->nullable()->after('gender'); // Single, Married, etc.
            $table->string('blood_group')->nullable()->after('marital_status');
            $table->string('nid')->nullable()->after('blood_group');
            $table->string('phone')->nullable()->after('nid');
            $table->string('email')->nullable()->after('phone');
            $table->text('present_address')->nullable()->after('email');
            $table->text('permanent_address')->nullable()->after('present_address');
            $table->string('photo')->nullable()->after('permanent_address');

            // Emergency Contact (Official/Personal overlap)
            $table->string('emergency_contact_name')->nullable()->after('photo');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation')->nullable()->after('emergency_contact_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'mother_name',
                'dob',
                'gender',
                'marital_status',
                'blood_group',
                'nid',
                'phone',
                'email',
                'present_address',
                'permanent_address',
                'photo',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relation',
            ]);
        });
    }
};
