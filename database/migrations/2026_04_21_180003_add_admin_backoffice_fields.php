<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('isAdmin');
            $table->timestamp('deactivated_at')->nullable()->after('is_active');
            $table->text('deactivation_reason')->nullable()->after('deactivated_at');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('organization_email');
            $table->boolean('is_publicly_visible')->default(true)->after('is_active');
            $table->timestamp('deactivated_at')->nullable()->after('is_publicly_visible');
            $table->text('deactivation_reason')->nullable()->after('deactivated_at');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('editorial_status')->default('published')->after('featured');
            $table->text('editorial_note')->nullable()->after('editorial_status');
            $table->boolean('is_breaking')->default(false)->after('editorial_note');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('source')->default('kkiapay')->after('payment_method');
            $table->string('reference')->nullable()->after('source');
            $table->timestamp('paid_at')->nullable()->after('reference');
            $table->unsignedInteger('months_awarded')->default(1)->after('paid_at');
            $table->foreignId('admin_id')->nullable()->after('months_awarded')->constrained('admins')->nullOnDelete();
            $table->text('notes')->nullable()->after('admin_id');
        });

        DB::table('posts')->whereNull('editorial_status')->update(['editorial_status' => 'published']);
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_id');
            $table->dropColumn(['source', 'reference', 'paid_at', 'months_awarded', 'notes']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['editorial_status', 'editorial_note', 'is_breaking']);
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'is_publicly_visible', 'deactivated_at', 'deactivation_reason']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'deactivated_at', 'deactivation_reason']);
        });
    }
};
