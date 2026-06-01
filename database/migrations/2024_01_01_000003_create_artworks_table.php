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
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('medium', ['oil', 'acrylic', 'watercolor', 'digital', 'photography', 'sculpture', 'mixed_media', 'other']);
            $table->string('dimensions'); // e.g., "24x36 inches"
            $table->decimal('price', 10, 2);
            $table->integer('year')->nullable();
            $table->json('images'); // Store multiple image paths
            $table->enum('status', ['draft', 'pending', 'approved', 'sold', 'resale'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('views')->default(0);
            $table->integer('likes_count')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index(['artist_id', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['is_featured', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
