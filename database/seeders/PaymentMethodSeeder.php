<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Stripe (Credit/Debit Card)',
                'code' => 'stripe',
                'type' => 'stripe',
                'is_active' => true,
                'requires_manual_verification' => false,
                'config' => ['public_key' => env('STRIPE_PUBLIC_KEY'), 'secret_key' => env('STRIPE_SECRET_KEY')],
                'sort_order' => 1,
            ],
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'type' => 'paypal',
                'is_active' => true,
                'requires_manual_verification' => false,
                'config' => ['client_id' => env('PAYPAL_CLIENT_ID'), 'secret' => env('PAYPAL_SECRET')],
                'sort_order' => 2,
            ],
            [
                'name' => 'KBZ Pay',
                'code' => 'kbz_pay',
                'type' => 'mobile_payment',
                'is_active' => true,
                'requires_manual_verification' => true,
                'instructions' => 'Please transfer to KBZ Pay account: 09-XXXXXXXXX. Upload screenshot after payment.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Wave Pay',
                'code' => 'wave_pay',
                'type' => 'mobile_payment',
                'is_active' => true,
                'requires_manual_verification' => true,
                'instructions' => 'Please transfer to Wave Pay account: 09-XXXXXXXXX. Upload screenshot after payment.',
                'sort_order' => 4,
            ],
            [
                'name' => 'AYA Pay',
                'code' => 'aya_pay',
                'type' => 'mobile_payment',
                'is_active' => true,
                'requires_manual_verification' => true,
                'instructions' => 'Please transfer to AYA Pay account: 09-XXXXXXXXX. Upload screenshot after payment.',
                'sort_order' => 5,
            ],
            [
                'name' => 'UAB Pay',
                'code' => 'uab_pay',
                'type' => 'mobile_payment',
                'is_active' => true,
                'requires_manual_verification' => true,
                'instructions' => 'Please transfer to UAB Pay account: 09-XXXXXXXXX. Upload screenshot after payment.',
                'sort_order' => 6,
            ],
            [
                'name' => 'MMQR',
                'code' => 'mmqr',
                'type' => 'mobile_payment',
                'is_active' => true,
                'requires_manual_verification' => true,
                'instructions' => 'Scan MMQR code and upload screenshot after payment.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Bank Transfer',
                'code' => 'bank_transfer',
                'type' => 'bank_transfer',
                'is_active' => true,
                'requires_manual_verification' => true,
                'instructions' => 'Bank: KBZ Bank\nAccount Name: Panchi Gallery\nAccount Number: 01234567890123456\nBranch: Yangon\n\nUpload bank slip or screenshot after transfer.',
                'sort_order' => 8,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(['code' => $method['code']], $method);
        }
    }
}
