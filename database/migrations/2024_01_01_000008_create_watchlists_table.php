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
        Schema::create('watchlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('watchable_type')->comment('Movie or Series class');
            $table->unsignedBigInteger('watchable_id');
            $table->timestamps();
            
            $table->index(['watchable_type', 'watchable_id']);
            $table->index('user_id');
            $table->unique(['user_id', 'watchable_type', 'watchable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlists');
    }
};