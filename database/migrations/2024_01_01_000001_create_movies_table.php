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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('poster_url')->nullable();
            $table->string('backdrop_url')->nullable();
            $table->year('release_year')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('duration')->nullable()->comment('Duration in minutes');
            $table->string('video_url')->nullable();
            $table->json('video_qualities')->nullable()->comment('Available video qualities with URLs');
            $table->integer('tmdb_id')->nullable()->unique()->comment('The Movie Database ID');
            $table->string('imdb_id')->nullable();
            $table->string('trailer_url')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('title');
            $table->index('release_year');
            $table->index('rating');
            $table->index('is_premium');
            $table->index('is_active');
            $table->index(['is_active', 'created_at']);
            $table->index('tmdb_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};