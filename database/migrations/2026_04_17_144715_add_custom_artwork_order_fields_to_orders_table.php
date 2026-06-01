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
        Schema::table('orders', function (Blueprint $table) {
            // Custom artwork order fields
            $table->foreignId('artist_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('size')->nullable();
            $table->string('medium')->nullable();
            $table->string('style')->nullable();
            $table->decimal('proposed_price', 10, 2)->nullable();
            $table->string('reference_image')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('artist_notes')->nullable();
            
            // Order status tracking for custom artwork
            $table->enum('order_type', ['regular', 'custom'])->default('regular');
            $table->enum('custom_status', [
                'pending_artist_approval',
                'artist_accepted',
                'artist_rejected',
                'in_progress',
                'ready_for_review',
                'customer_approved',
                'customer_rejected',
                'shipped',
                'delivered',
                'cancelled'
            ])->nullable();
            
            // Timeline tracking
            $table->timestamp('artist_accepted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Indexes
            $table->index(['artist_id', 'custom_status']);
            $table->index(['user_id', 'order_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['artist_id']);
            $table->dropIndex(['artist_id', 'custom_status']);
            $table->dropIndex(['user_id', 'order_type']);
            
            $table->dropColumn([
                'artist_id',
                'title',
                'description',
                'size',
                'medium',
                'style',
                'proposed_price',
                'reference_image',
                'customer_notes',
                'artist_notes',
                'order_type',
                'custom_status',
                'artist_accepted_at',
                'completed_at',
                'shipped_at',
                'delivered_at'
            ]);
        });
    }
};
