<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reader_favorites', function (Blueprint $table) {
            $table->id();
            $table->string('user_type', 20); // reader | web | advertiser | admin
            $table->unsignedBigInteger('user_id');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_type', 'user_id', 'post_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('reader_favorites');
    }
};
