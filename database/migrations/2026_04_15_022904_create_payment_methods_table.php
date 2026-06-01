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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Stripe', 'PayPal', 'KBZ Pay'
            $table->string('code')->unique(); // e.g., 'stripe', 'paypal', 'kbz_pay'
            $table->enum('type', ['stripe', 'paypal', 'bank_transfer', 'mobile_payment']);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_manual_verification')->default(false);
            $table->json('config')->nullable(); // Store payment gateway config
            $table->text('instructions')->nullable(); // Payment instructions for manual methods
            $table->string('logo')->nullable(); // Payment method logo
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
