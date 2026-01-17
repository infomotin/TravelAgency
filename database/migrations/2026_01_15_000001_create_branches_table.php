<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('agency_id')->constrained('agencies')->cascadeOnDelete();
                $table->string('name');
                $table->string('code')->nullable()->index();
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active')->index();
                $table->softDeletes();
                $table->timestamps();
                $table->unique(['agency_id', 'name']);
            });
        } else {
            Schema::table('branches', function (Blueprint $table) {
                if (! Schema::hasColumn('branches', 'agency_id')) {
                    $table->foreignId('agency_id')->nullable()->after('id');
                }
                if (! Schema::hasColumn('branches', 'code')) {
                    $table->string('code')->nullable()->after('name');
                }
                if (! Schema::hasColumn('branches', 'address')) {
                    $table->string('address')->nullable()->after('code');
                }
                if (! Schema::hasColumn('branches', 'phone')) {
                    $table->string('phone')->nullable()->after('address');
                }
                if (! Schema::hasColumn('branches', 'status')) {
                    $table->enum('status', ['active', 'inactive'])->default('active')->after('phone');
                }
                if (! Schema::hasColumn('branches', 'deleted_at')) {
                    $table->softDeletes();
                }
                if (! Schema::hasColumn('branches', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
