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
        Schema::create('watch_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('watchable_type')->comment('Movie or Episode class');
            $table->unsignedBigInteger('watchable_id');
            $table->integer('progress')->default(0)->comment('Progress in seconds');
            $table->integer('duration')->nullable()->comment('Total duration in seconds');
            $table->boolean('completed')->default(false);
            $table->timestamp('last_watched_at');
            $table->timestamps();
            
            $table->index(['watchable_type', 'watchable_id']);
            $table->index('user_id');
            $table->index('last_watched_at');
            $table->index(['user_id', 'completed']);
            $table->unique(['user_id', 'watchable_type', 'watchable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watch_history');
    }
};