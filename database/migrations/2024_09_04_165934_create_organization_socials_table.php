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
        Schema::create('organization_socials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_id')->constrained('socials'); 
            $table->foreignId('organization_id')->constrained('organizations');
            $table->string('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_socials');
    }
};
