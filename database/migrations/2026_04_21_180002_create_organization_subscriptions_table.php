<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('plan_name')->default('Blog Standard');
            $table->string('status')->default('inactive');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('next_renewal_at')->nullable();
            $table->unsignedInteger('renewal_cycle_months')->default(1);
            $table->boolean('is_auto_renew')->default(false);
            $table->timestamp('last_payment_at')->nullable();
            $table->foreignId('managed_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('organization_id');
        });

        $users = DB::table('users')
            ->select('organization_id', 'subscription_started_at', 'subscription_quantity')
            ->whereNotNull('organization_id')
            ->orderBy('id')
            ->get()
            ->unique('organization_id');

        foreach ($users as $user) {
            $startedAt = $user->subscription_started_at ? Carbon::parse($user->subscription_started_at) : null;
            $months = max(1, (int) ($user->subscription_quantity ?? 1));
            $expiresAt = $startedAt ? $startedAt->copy()->addMonths($months) : null;

            DB::table('organization_subscriptions')->insert([
                'organization_id' => $user->organization_id,
                'plan_name' => 'Blog Standard',
                'status' => $expiresAt && now()->lessThanOrEqualTo($expiresAt) ? 'active' : 'inactive',
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
                'next_renewal_at' => $expiresAt,
                'renewal_cycle_months' => 1,
                'is_auto_renew' => false,
                'last_payment_at' => $startedAt,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_subscriptions');
    }
};
