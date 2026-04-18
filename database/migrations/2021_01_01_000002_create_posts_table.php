<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->text('libelle');
            $table->string('sous_titre', 255)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->string('image', 191)->nullable();
            $table->string('audio', 191)->nullable();
            $table->string('video', 191)->nullable();
            $table->timestamps();
            $table->string('images_url', 191)->nullable();
            $table->string('data_url', 191)->nullable();
            $table->text('slug')->nullable();
            $table->smallInteger('featured')->default(0);
            $table->string('necro_movie', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
