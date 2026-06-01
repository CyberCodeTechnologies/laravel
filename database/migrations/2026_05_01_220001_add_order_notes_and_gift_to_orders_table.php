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
            if (!Schema::hasColumn('orders', 'order_notes')) {
                $table->text('order_notes')->nullable()->after('shipping_method');
            }
            if (!Schema::hasColumn('orders', 'is_gift')) {
                $table->boolean('is_gift')->default(false)->after('order_notes');
            }
            if (!Schema::hasColumn('orders', 'gift_message')) {
                $table->text('gift_message')->nullable()->after('is_gift');
            }
            if (!Schema::hasColumn('orders', 'gift_receipt')) {
                $table->boolean('gift_receipt')->default(false)->after('gift_message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_notes', 'is_gift', 'gift_message', 'gift_receipt']);
        });
    }
};
