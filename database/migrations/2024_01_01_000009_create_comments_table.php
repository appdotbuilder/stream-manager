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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('commentable_type')->comment('Movie or Episode class');
            $table->unsignedBigInteger('commentable_id');
            $table->text('content');
            $table->decimal('rating', 3, 1)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            $table->index(['commentable_type', 'commentable_id']);
            $table->index('user_id');
            $table->index('is_approved');
            $table->index(['is_approved', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};