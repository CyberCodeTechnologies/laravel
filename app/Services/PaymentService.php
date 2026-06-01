<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process payment for an order.
     *
     * @param Order $order
     * @param string $paymentMethod
     * @param array $paymentDetails
     * @return array
     */
    /**
     * Myanmar mobile payment methods that require manual verification.
     */
    protected array $myanmarPaymentMethods = [
        'kbz_pay',
        'wave_pay',
        'aya_pay',
        'uab_pay',
        'mmqr',
        'bank_transfer',
    ];

    public function processPayment(Order $order, string $paymentMethod, array $paymentDetails = []): array
    {
        try {
            // Check if this is a Myanmar manual payment method
            if (in_array($paymentMethod, $this->myanmarPaymentMethods)) {
                return $this->processMyanmarManualPayment($order, $paymentMethod);
            }

            switch ($paymentMethod) {
                case 'stripe':
                    return $this->processStripePayment($order, $paymentDetails);
                case 'paypal':
                    return $this->processPayPalPayment($order, $paymentDetails);
                default:
                    return [
                        'success' => false,
                        'message' => 'Invalid payment method',
                    ];
            }
        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment processing failed. Please try again.',
            ];
        }
    }

    /**
     * Process Myanmar manual payment methods (KBZ Pay, Wave Pay, etc.).
     * These require manual verification with payment proof upload.
     *
     * @param Order $order
     * @param string $paymentMethod
     * @return array
     */
    protected function processMyanmarManualPayment(Order $order, string $paymentMethod): array
    {
        // For manual payments, the order stays in 'pending' status
        // until the customer uploads payment proof
        // No automatic status change here - admin will verify later

        $methodNames = [
            'kbz_pay' => 'KBZ Pay',
            'wave_pay' => 'Wave Pay',
            'aya_pay' => 'AYA Pay',
            'uab_pay' => 'UAB Pay',
            'mmqr' => 'MMQR',
            'bank_transfer' => 'Bank Transfer',
        ];

        $methodName = $methodNames[$paymentMethod] ?? 'Manual Payment';

        Log::info("Myanmar manual payment initiated: {$methodName} for Order #{$order->id}");

        return [
            'success' => true,
            'message' => "Please complete payment via {$methodName} and upload your payment proof.",
            'transaction_id' => null,
            'requires_upload' => true,
        ];
    }

    /**
     * Process Stripe payment.
     *
     * @param Order $order
     * @param array $paymentDetails
     * @return array
     */
    protected function processStripePayment(Order $order, array $paymentDetails): array
    {
        // In a real implementation, you would use Stripe SDK here
        // For now, we'll simulate a successful payment
        
        // Simulate payment processing
        sleep(1);
        
        // Update order status
        $order->update([
            'status' => 'processing',
        ]);

        return [
            'success' => true,
            'message' => 'Payment processed successfully via Stripe',
            'transaction_id' => 'stripe_' . uniqid(),
        ];
    }

    /**
     * Process PayPal payment.
     *
     * @param Order $order
     * @param array $paymentDetails
     * @return array
     */
    protected function processPayPalPayment(Order $order, array $paymentDetails): array
    {
        // In a real implementation, you would use PayPal SDK here
        // For now, we'll simulate a successful payment
        
        // Simulate payment processing
        sleep(1);
        
        // Update order status
        $order->update([
            'status' => 'processing',
        ]);

        return [
            'success' => true,
            'message' => 'Payment processed successfully via PayPal',
            'transaction_id' => 'paypal_' . uniqid(),
        ];
    }

    /**
     * Refund payment.
     *
     * @param Order $order
     * @return array
     */
    public function refundPayment(Order $order): array
    {
        try {
            // In a real implementation, you would call Stripe/PayPal refund API
            // For now, we'll simulate a successful refund
            
            $order->update([
                'status' => 'refunded',
            ]);

            return [
                'success' => true,
                'message' => 'Refund processed successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Refund processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Refund processing failed. Please try again.',
            ];
        }
    }

    /**
     * Get supported payment methods.
     *
     * @return array
     */
    public function getSupportedPaymentMethods(): array
    {
        return [
            'stripe' => [
                'name' => 'Credit/Debit Card',
                'description' => 'Pay securely with your credit or debit card',
                'icon' => 'credit-card',
                'requires_details' => true,
                'type' => 'international',
            ],
            'paypal' => [
                'name' => 'PayPal',
                'description' => 'Pay with your PayPal account',
                'icon' => 'paypal',
                'requires_details' => false,
                'type' => 'international',
            ],
            'kbz_pay' => [
                'name' => 'KBZ Pay',
                'description' => 'Pay with KBZ Pay mobile wallet',
                'icon' => 'wallet',
                'requires_details' => false,
                'type' => 'myanmar',
                'requires_manual_verification' => true,
            ],
            'wave_pay' => [
                'name' => 'Wave Pay',
                'description' => 'Pay with Wave Pay mobile wallet',
                'icon' => 'wallet',
                'requires_details' => false,
                'type' => 'myanmar',
                'requires_manual_verification' => true,
            ],
            'aya_pay' => [
                'name' => 'AYA Pay',
                'description' => 'Pay with AYA Pay mobile wallet',
                'icon' => 'wallet',
                'requires_details' => false,
                'type' => 'myanmar',
                'requires_manual_verification' => true,
            ],
            'uab_pay' => [
                'name' => 'UAB Pay',
                'description' => 'Pay with UAB Pay mobile wallet',
                'icon' => 'wallet',
                'requires_details' => false,
                'type' => 'myanmar',
                'requires_manual_verification' => true,
            ],
            'mmqr' => [
                'name' => 'MMQR',
                'description' => 'Scan and pay with MMQR code',
                'icon' => 'qrcode',
                'requires_details' => false,
                'type' => 'myanmar',
                'requires_manual_verification' => true,
            ],
            'bank_transfer' => [
                'name' => 'Bank Transfer',
                'description' => 'Direct bank transfer',
                'icon' => 'university',
                'requires_details' => false,
                'type' => 'myanmar',
                'requires_manual_verification' => true,
            ],
        ];
    }

    /**
     * Validate payment details.
     *
     * @param string $paymentMethod
     * @param array $paymentDetails
     * @return array
     */
    public function validatePaymentDetails(string $paymentMethod, array $paymentDetails): array
    {
        if ($paymentMethod === 'stripe') {
            return $this->validateStripeDetails($paymentDetails);
        }

        return ['valid' => true];
    }

    /**
     * Validate Stripe payment details.
     *
     * @param array $details
     * @return array
     */
    protected function validateStripeDetails(array $details): array
    {
        $required = ['card_number', 'expiry_month', 'expiry_year', 'cvv'];
        
        foreach ($required as $field) {
            if (!isset($details[$field]) || empty($details[$field])) {
                return [
                    'valid' => false,
                    'message' => "Missing required field: {$field}",
                ];
            }
        }

        // Basic validation
        if (strlen($details['card_number']) < 13 || strlen($details['card_number']) > 19) {
            return [
                'valid' => false,
                'message' => 'Invalid card number',
            ];
        }

        if ($details['expiry_month'] < 1 || $details['expiry_month'] > 12) {
            return [
                'valid' => false,
                'message' => 'Invalid expiry month',
            ];
        }

        if (strlen($details['cvv']) < 3 || strlen($details['cvv']) > 4) {
            return [
                'valid' => false,
                'message' => 'Invalid CVV',
            ];
        }

        return ['valid' => true];
    }
}
