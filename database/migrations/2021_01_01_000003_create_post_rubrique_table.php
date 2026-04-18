<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_rubrique', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('rubrique_id')->constrained('rubriques')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_rubrique');
    }
};
