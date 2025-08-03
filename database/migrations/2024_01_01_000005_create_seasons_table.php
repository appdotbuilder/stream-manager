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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('season_number');
            $table->text('description')->nullable();
            $table->string('poster_url')->nullable();
            $table->year('release_year')->nullable();
            $table->integer('tmdb_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['series_id', 'season_number']);
            $table->index('series_id');
            $table->index('season_number');
            $table->index('is_active');
            $table->index('tmdb_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};