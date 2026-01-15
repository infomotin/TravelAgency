<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('agency_id')->nullable()->constrained('agencies')->nullOnDelete()->index();
                $table->foreignId('branch_id')->nullable()->index();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamp('email_verified_at')->nullable();
                $table->json('meta')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'agency_id')) {
                    $table->foreignId('agency_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('users', 'branch_id')) {
                    $table->foreignId('branch_id')->nullable()->after('agency_id');
                }
                if (!Schema::hasColumn('users', 'meta')) {
                    $table->json('meta')->nullable()->after('email_verified_at');
                }
                if (!Schema::hasColumn('users', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
