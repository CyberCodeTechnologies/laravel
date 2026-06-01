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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->foreignId('artist_id')->constrained('users')->onDelete('cascade');
            $table->decimal('sale_amount', 12, 2); // Total sale amount
            $table->decimal('platform_fee', 12, 2); // Platform commission
            $table->decimal('artist_earnings', 12, 2); // Artist's share
            $table->decimal('platform_fee_percentage', 5, 2)->default(30.00); // Commission rate
            $table->enum('status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable(); // When commission was processed
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['artist_id', 'status']);
            $table->index(['transaction_id']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
