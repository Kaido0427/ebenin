<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name', 191)->nullable();
            $table->string('organization_address', 191)->nullable();
            $table->string('organization_phone', 191)->nullable();
            $table->string('organization_logo', 191)->nullable();
            $table->string('organization_email', 191)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
