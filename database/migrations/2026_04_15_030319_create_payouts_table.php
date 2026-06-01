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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 12, 2); // Total payout amount
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->enum('method', ['bank_transfer', 'paypal', 'stripe'])->default('bank_transfer');
            $table->string('payment_reference')->nullable(); // External payment ID
            $table->json('commission_ids')->nullable(); // Array of commission IDs included
            $table->integer('commission_count')->default(0); // Number of commissions
            $table->text('notes')->nullable();
            $table->text('failure_reason')->nullable(); // If payout failed
            $table->timestamp('requested_at')->nullable(); // When artist requested
            $table->timestamp('processed_at')->nullable(); // When admin processed
            $table->foreignId('processed_by')->nullable()->constrained('users'); // Admin who processed
            $table->timestamps();
            
            $table->index(['artist_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['payment_reference']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
