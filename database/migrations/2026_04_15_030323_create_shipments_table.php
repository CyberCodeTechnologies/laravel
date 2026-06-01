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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('tracking_number')->nullable();
            $table->string('carrier'); // DHL, FedEx, UPS, Local Courier
            $table->string('service')->nullable(); // Express, Standard, etc.
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'label_created', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'exception'])->default('pending');
            $table->json('tracking_history')->nullable(); // Array of tracking events
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('estimated_delivery')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('shipping_address');
            $table->text('notes')->nullable();
            $table->string('label_url')->nullable(); // Shipping label PDF URL
            $table->timestamps();
            
            $table->index(['order_id']);
            $table->index(['tracking_number']);
            $table->index(['status']);
            $table->index(['carrier', 'tracking_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
