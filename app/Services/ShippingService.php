<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;

class ShippingService
{
    /**
     * Calculate shipping cost for a cart.
     *
     * @param Cart $cart
     * @param string $method
     * @return float
     */
    public function calculateCartShipping(Cart $cart, string $method = 'standard'): float
    {
        $cart->load('items.artwork');
        
        if ($cart->items->isEmpty()) {
            return 0;
        }

        $totalShipping = 0;
        $hasPhysicalItems = false;

        foreach ($cart->items as $item) {
            if (!$item->artwork->is_digital) {
                $hasPhysicalItems = true;
                $totalShipping += $item->artwork->calculateShippingCost($method);
            }
        }

        // If all items are digital, no shipping cost
        if (!$hasPhysicalItems) {
            return 0;
        }

        // Apply volume discount for multiple physical items
        if ($cart->items->where('artwork.is_digital', false)->count() > 1) {
            $totalShipping *= 0.8; // 20% discount for multiple items
        }

        return round($totalShipping, 2);
    }

    /**
     * Get shipping method details.
     *
     * @return array
     */
    public function getShippingMethods(): array
    {
        return [
            'standard' => [
                'name' => 'Standard Shipping',
                'description' => '10-14 business days',
                'base_rate' => 50,
                'icon' => 'truck',
            ],
            'express' => [
                'name' => 'Express Shipping',
                'description' => '5-7 business days',
                'base_rate' => 100,
                'icon' => 'bolt',
            ],
            'premium' => [
                'name' => 'Premium Shipping',
                'description' => '1-2 business days',
                'base_rate' => 200,
                'icon' => 'star',
            ],
        ];
    }

    /**
     * Get shipping method by key.
     *
     * @param string $method
     * @return array|null
     */
    public function getShippingMethod(string $method): ?array
    {
        return $this->getShippingMethods()[$method] ?? null;
    }

    /**
     * Check if shipping is free for cart.
     *
     * @param Cart $cart
     * @return bool
     */
    public function isFreeShipping(Cart $cart): bool
    {
        // Free shipping for orders over $500
        return $cart->total_amount >= 500;
    }
}
