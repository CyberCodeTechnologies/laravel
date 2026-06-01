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
        Schema::create('resales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->decimal('asking_price', 10, 2);
            $table->decimal('minimum_price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->json('images')->nullable(); // Additional images for resale listing
            $table->enum('status', ['pending', 'approved', 'listed', 'sold', 'withdrawn'])->default('pending');
            $table->timestamp('listed_at')->nullable();
            $table->timestamp('sold_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index(['owner_id', 'status']);
            $table->index(['artwork_id', 'status']);
            $table->index(['status', 'listed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resales');
    }
};
