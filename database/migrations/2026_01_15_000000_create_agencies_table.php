<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('agencies')) {
            Schema::create('agencies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('logo_path')->nullable();
                $table->string('address')->nullable();
                $table->string('vat_number')->nullable();
                $table->string('currency', 3)->default('USD');
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->index();
                $table->timestamp('subscription_expires_at')->nullable()->index();
                $table->json('settings')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        } else {
            Schema::table('agencies', function (Blueprint $table) {
                if (! Schema::hasColumn('agencies', 'slug')) {
                    $table->string('slug')->nullable()->after('name');
                }
                if (! Schema::hasColumn('agencies', 'logo_path')) {
                    $table->string('logo_path')->nullable()->after('slug');
                }
                if (! Schema::hasColumn('agencies', 'address')) {
                    $table->string('address')->nullable()->after('logo_path');
                }
                if (! Schema::hasColumn('agencies', 'vat_number')) {
                    $table->string('vat_number')->nullable()->after('address');
                }
                if (! Schema::hasColumn('agencies', 'currency')) {
                    $table->string('currency', 3)->default('USD')->after('vat_number');
                }
                if (! Schema::hasColumn('agencies', 'status')) {
                    $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('currency');
                }
                if (! Schema::hasColumn('agencies', 'subscription_expires_at')) {
                    $table->timestamp('subscription_expires_at')->nullable()->after('status');
                }
                if (! Schema::hasColumn('agencies', 'settings')) {
                    $table->json('settings')->nullable()->after('subscription_expires_at');
                }
                if (! Schema::hasColumn('agencies', 'deleted_at')) {
                    $table->softDeletes();
                }
                if (! Schema::hasColumn('agencies', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
