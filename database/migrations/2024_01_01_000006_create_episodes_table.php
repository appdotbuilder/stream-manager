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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('episode_number');
            $table->text('description')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->string('video_url')->nullable();
            $table->json('video_qualities')->nullable()->comment('Available video qualities with URLs');
            $table->decimal('rating', 3, 1)->default(0);
            $table->date('air_date')->nullable();
            $table->integer('tmdb_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['season_id', 'episode_number']);
            $table->index('season_id');
            $table->index('episode_number');
            $table->index('air_date');
            $table->index('is_active');
            $table->index('tmdb_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};