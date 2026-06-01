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
        Schema::create('ownerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained()->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('acquired_at');
            $table->boolean('is_current_owner')->default(true);
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('transaction_type')->default('sale'); // sale, transfer, inheritance
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['artwork_id', 'is_current_owner']);
            $table->index(['owner_id', 'is_current_owner']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ownerships');
    }
};
