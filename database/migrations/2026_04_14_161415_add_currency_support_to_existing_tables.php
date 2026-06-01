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
        // Add currency columns to artworks table
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

        // Check if transactions table exists and has the right structure
        if (Schema::hasTable('transactions')) {
            // Add currency columns to transactions table
            Schema::table('transactions', function (Blueprint $table) {
                // Check if amount column exists, if not add price column
                if (!Schema::hasColumn('transactions', 'price') && !Schema::hasColumn('transactions', 'amount')) {
                    $table->decimal('price', 10, 2)->default(0);
                } elseif (Schema::hasColumn('transactions', 'amount') && !Schema::hasColumn('transactions', 'price')) {
                    // Rename amount to price for consistency
                    $table->renameColumn('amount', 'price');
                }
                
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
        }

        // Check if resales table exists and add currency columns
        if (Schema::hasTable('resales')) {
            Schema::table('resales', function (Blueprint $table) {
                // Check if asking_price exists, if not add price column
                if (!Schema::hasColumn('resales', 'price') && !Schema::hasColumn('resales', 'asking_price')) {
                    $table->decimal('price', 10, 2)->default(0);
                } elseif (Schema::hasColumn('resales', 'asking_price') && !Schema::hasColumn('resales', 'price')) {
                    // Rename asking_price to price for consistency
                    $table->renameColumn('asking_price', 'price');
                }
                
                if (!Schema::hasColumn('resales', 'currency')) {
                    $table->string('currency', 3)->default('USD')->after('price');
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
        }

        // Create exchange rates table for historical tracking
        if (!Schema::hasTable('exchange_rates')) {
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop exchange rates table
        Schema::dropIfExists('exchange_rates');

        // Remove currency columns from resales table if it exists
        if (Schema::hasTable('resales')) {
            Schema::table('resales', function (Blueprint $table) {
                $table->dropColumn(['currency', 'price_usd', 'price_mmk', 'exchange_rate']);
                // Optionally rename price back to asking_price if needed
                if (Schema::hasColumn('resales', 'price')) {
                    $table->renameColumn('price', 'asking_price');
                }
            });
        }

        // Remove currency columns from transactions table if it exists
        if (Schema::hasTable('transactions')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->dropColumn(['currency', 'price_usd', 'price_mmk', 'exchange_rate']);
                // Optionally rename price back to amount if needed
                if (Schema::hasColumn('transactions', 'price')) {
                    $table->renameColumn('price', 'amount');
                }
            });
        }

        // Remove currency columns from artworks table
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['currency', 'price_usd', 'price_mmk']);
        });
    }
};
