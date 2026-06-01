<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PromoCode;
use Carbon\Carbon;

class PromoCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 10% off promo code
        PromoCode::create([
            'code' => 'SAVE10',
            'description' => '10% off your order',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 50,
            'usage_limit' => 100,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(3),
            'is_active' => true,
        ]);

        // 20% off promo code
        PromoCode::create([
            'code' => 'SAVE20',
            'description' => '20% off orders over $200',
            'type' => 'percentage',
            'value' => 20,
            'min_order_amount' => 200,
            'max_discount_amount' => 100,
            'usage_limit' => 50,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(6),
            'is_active' => true,
        ]);

        // $50 fixed discount
        PromoCode::create([
            'code' => 'FLAT50',
            'description' => '$50 off orders over $300',
            'type' => 'fixed',
            'value' => 50,
            'min_order_amount' => 300,
            'usage_limit' => 30,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonths(2),
            'is_active' => true,
        ]);

        // Welcome code for new customers
        PromoCode::create([
            'code' => 'WELCOME',
            'description' => '15% off your first order',
            'type' => 'percentage',
            'value' => 15,
            'min_order_amount' => 0,
            'usage_limit' => 200,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addYear(),
            'is_active' => true,
        ]);
    }
}
