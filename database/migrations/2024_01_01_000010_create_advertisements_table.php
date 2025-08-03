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
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('code')->comment('Google AdSense code');
            $table->enum('placement', ['homepage_hero', 'homepage_sidebar', 'movie_detail_top', 'movie_detail_bottom', 'player_overlay'])->default('homepage_hero');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('placement');
            $table->index('is_active');
            $table->index(['placement', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};