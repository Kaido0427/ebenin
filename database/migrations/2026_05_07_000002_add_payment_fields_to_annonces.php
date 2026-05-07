<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('payment_ref')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_ref']);
        });
    }
};
