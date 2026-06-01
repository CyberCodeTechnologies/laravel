<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }

    public function getOrCreateForUser(int $userId): Cart
    {
        return $this->model->firstOrCreate(['user_id' => $userId]);
    }

    public function getWithItems(int $cartId): ?Cart
    {
        return $this->model->with('items.artwork')->find($cartId);
    }

    public function addItem(int $cartId, array $itemData): bool
    {
        $cart = $this->find($cartId);
        if ($cart) {
            return $cart->items()->create($itemData) !== null;
        }
        return false;
    }

    public function updateItem(int $cartItemId, array $data): bool
    {
        $cartItem = \App\Models\CartItem::find($cartItemId);
        if ($cartItem) {
            return $cartItem->update($data);
        }
        return false;
    }

    public function removeItem(int $cartItemId): bool
    {
        $cartItem = \App\Models\CartItem::find($cartItemId);
        if ($cartItem) {
            return $cartItem->delete();
        }
        return false;
    }

    public function clearCart(int $cartId): bool
    {
        $cart = $this->find($cartId);
        if ($cart) {
            return $cart->items()->delete() !== false;
        }
        return false;
    }

    public function getTotal(int $cartId): float
    {
        $cart = $this->getWithItems($cartId);
        if ($cart) {
            return $cart->items->sum(function ($item) {
                return $item->artwork->price * $item->quantity;
            });
        }
        return 0.0;
    }
}
