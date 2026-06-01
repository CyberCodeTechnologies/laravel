<?php

namespace App\Repositories\Interfaces;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface extends RepositoryInterface
{
    /**
     * Get or create cart for user.
     */
    public function getOrCreateForUser(int $userId): Cart;

    /**
     * Get cart with items.
     */
    public function getWithItems(int $cartId): ?Cart;

    /**
     * Add item to cart.
     */
    public function addItem(int $cartId, array $itemData): bool;

    /**
     * Update cart item.
     */
    public function updateItem(int $cartItemId, array $data): bool;

    /**
     * Remove item from cart.
     */
    public function removeItem(int $cartItemId): bool;

    /**
     * Clear cart.
     */
    public function clearCart(int $cartId): bool;

    /**
     * Get cart total.
     */
    public function getTotal(int $cartId): float;
}
