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
        // Add currency column to artworks table
        Schema::table('artworks', function (Blueprint $table) {
            if (!Schema::hasColumn('artworks', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('price');
            }
            if (!Schema::hasColumn('artworks', 'price_usd')) {
                $table->decimal('price_usd', 10, 2)->nullable()->after('currency');
            }
            if (!Schema::hasColumn('artworks', 'price_mmk')) {
                $table->decimal('price_mmk', 12, 0)->nullable()->after('price_usd');
            }
        });

        // Add currency column to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('price');
            }
            if (!Schema::hasColumn('transactions', 'price_usd')) {
                $table->decimal('price_usd', 10, 2)->nullable()->after('currency');
            }
            if (!Schema::hasColumn('transactions', 'price_mmk')) {
                $table->decimal('price_mmk', 12, 0)->nullable()->after('price_usd');
            }
            if (!Schema::hasColumn('transactions', 'exchange_rate')) {
                $table->decimal('exchange_rate', 10, 6)->nullable()->after('price_mmk');
            }
        });

        // Add currency column to resales table
        Schema::table('resales', function (Blueprint $table) {
            if (!Schema::hasColumn('resales', 'currency')) {
                $table->string('currency', 3)->default('USD')->after('asking_price');
            }
            if (!Schema::hasColumn('resales', 'price_usd')) {
                $table->decimal('price_usd', 10, 2)->nullable()->after('currency');
            }
            if (!Schema::hasColumn('resales', 'price_mmk')) {
                $table->decimal('price_mmk', 12, 0)->nullable()->after('price_usd');
            }
            if (!Schema::hasColumn('resales', 'exchange_rate')) {
                $table->decimal('exchange_rate', 10, 6)->nullable()->after('price_mmk');
            }
        });

        // Create currency exchange rates table for historical tracking
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3);
            $table->string('to_currency', 3);
            $table->decimal('rate', 10, 6);
            $table->timestamp('effective_date');
            $table->timestamps();

            $table->index(['from_currency', 'to_currency', 'effective_date']);
            $table->unique(['from_currency', 'to_currency', 'effective_date'], 'unique_exchange_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resales', function (Blueprint $table) {
            $table->dropColumn(['currency', 'price_usd', 'price_mmk', 'exchange_rate']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['currency', 'price_usd', 'price_mmk', 'exchange_rate']);
        });

        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['currency', 'price_usd', 'price_mmk']);
        });

        Schema::dropIfExists('exchange_rates');
    }
};
