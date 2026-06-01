<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Artwork;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display shopping cart.
     */
    public function index()
    {
        $cart = Cart::getOrCreateCart();
        $cart->load('items.artwork');

        return view('cart.index', compact('cart'));
    }

    /**
     * Add item to cart.
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'artwork_id' => 'required|exists:artworks,id',
            'quantity' => 'sometimes|integer|min:1|max:10',
        ]);

        $quantity = $request->input('quantity', 1);
        $cart = Cart::getOrCreateCart();
        $artwork = Artwork::findOrFail($request->artwork_id);

        // Check stock availability
        if (!$artwork->hasStock($quantity)) {
            return response()->json([
                'success' => false,
                'message' => __('Insufficient stock. Only :count items available.', ['count' => $artwork->getAvailableStock()]),
            ], 400);
        }

        // Check if item already exists in cart
        $existingItem = $cart->items()->where('artwork_id', $artwork->id)->first();
        
        if ($existingItem) {
            // Check if new total quantity exceeds stock
            $newQuantity = $existingItem->quantity + $quantity;
            if (!$artwork->hasStock($newQuantity)) {
                return response()->json([
                    'success' => false,
                    'message' => __('Cannot add more items. Maximum :count items available.', ['count' => $artwork->getAvailableStock()]),
                ], 400);
            }

            // Update quantity
            $existingItem->quantity = $newQuantity;
            $existingItem->subtotal = $existingItem->price * $newQuantity;
            $existingItem->save();
            
            $cart->updateTotal();
        } else {
            // Add new item
            $cartItem = $cart->items()->create([
                'artwork_id' => $artwork->id,
                'artwork_title' => $artwork->title,
                'price' => $artwork->price,
                'currency' => $artwork->currency ?? 'USD',
                'quantity' => $quantity,
                'subtotal' => $artwork->price * $quantity,
            ]);
            
            $cart->updateTotal();
        }

        return response()->json([
            'success' => true,
            'message' => __('Item added to cart'),
            'cart' => $cart->fresh(['items.artwork']),
        ]);
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $newQuantity = $request->quantity;
        
        // Check stock availability before updating
        $artwork = $cartItem->artwork;
        if (!$artwork->hasStock($newQuantity)) {
            return response()->json([
                'success' => false,
                'message' => __('Insufficient stock. Only :count items available.', ['count' => $artwork->getAvailableStock()]),
            ], 400);
        }

        $cartItem->quantity = $newQuantity;
        $cartItem->subtotal = $cartItem->price * $newQuantity;
        $cartItem->save();

        $cart = $cartItem->cart;
        $cart->updateTotal();

        return response()->json([
            'success' => true,
            'message' => __('Cart updated'),
            'cart' => $cart->fresh(['items.artwork']),
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function remove(int $cartItemId): JsonResponse
    {
        $cart = Cart::getOrCreateCart();

        // Find the cart item that belongs to the current cart
        $cartItem = CartItem::where('id', $cartItemId)
            ->where('cart_id', $cart->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => __('Item not found in cart'),
            ], 404);
        }

        $cartItem->delete();
        $cart->updateTotal();

        return response()->json([
            'success' => true,
            'message' => __('Item removed from cart'),
            'cart' => $cart->fresh(['items.artwork']),
        ]);
    }

    /**
     * Clear cart.
     */
    public function clear(): JsonResponse
    {
        $cart = Cart::getOrCreateCart();
        $cart->items()->delete();
        $cart->updateTotal();

        return response()->json([
            'success' => true,
            'message' => __('Cart cleared'),
            'cart' => $cart->fresh(),
        ]);
    }

    /**
     * Get cart item count.
     */
    public function count(): JsonResponse
    {
        try {
            $cart = Cart::getOrCreateCart();
            $count = $cart->items()->sum('quantity');

            return response()->json([
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'count' => 0,
                'error' => 'Failed to get cart count'
            ], 500);
        }
    }

    /**
     * Apply promo code to cart.
     */
    public function applyPromoCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $cart = Cart::getOrCreateCart();
        $promoCode = PromoCode::findByCode($request->code);

        if (!$promoCode) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid promo code'),
            ], 400);
        }

        if (!$promoCode->isValid()) {
            return response()->json([
                'success' => false,
                'message' => __('This promo code has expired or is no longer valid'),
            ], 400);
        }

        $subtotal = $cart->items()->sum('subtotal');

        if (!$promoCode->canApplyTo($subtotal)) {
            return response()->json([
                'success' => false,
                'message' => __('This promo code requires a minimum order of :amount', ['amount' => $promoCode->min_order_amount]),
            ], 400);
        }

        // Apply promo code
        if ($cart->applyPromoCode($promoCode)) {
            return response()->json([
                'success' => true,
                'message' => __('Promo code applied successfully'),
                'promo_code' => $promoCode->code,
                'discount_amount' => $cart->discount_amount,
                'formatted_discount' => $cart->formatted_discount,
                'subtotal_amount' => $cart->subtotal_amount,
                'formatted_subtotal' => $cart->formatted_subtotal,
                'total_amount' => $cart->total_amount,
                'formatted_total' => $cart->formatted_total,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('Failed to apply promo code'),
        ], 400);
    }

    /**
     * Remove promo code from cart.
     */
    public function removePromoCode(): JsonResponse
    {
        $cart = Cart::getOrCreateCart();
        $cart->removePromoCode();

        return response()->json([
            'success' => true,
            'message' => __('Promo code removed'),
            'total_amount' => $cart->total_amount,
            'formatted_total' => $cart->formatted_total,
        ]);
    }

    /**
     * Get cart summary for checkout/mini cart.
     */
    public function summary(): JsonResponse
    {
        $cart = Cart::getOrCreateCart();
        $cart->load('items.artwork');

        return response()->json([
            'success' => true,
            'cart' => [
                'id' => $cart->id,
                'item_count' => $cart->item_count,
                'subtotal_amount' => $cart->subtotal_amount,
                'discount_amount' => $cart->discount_amount,
                'total_amount' => $cart->total_amount,
                'currency' => $cart->currency,
                'promo_code' => $cart->promo_code,
                'formatted_subtotal' => $cart->formatted_subtotal,
                'formatted_discount' => $cart->formatted_discount,
                'formatted_total' => $cart->formatted_total,
            ],
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'artwork_id' => $item->artwork_id,
                    'artwork_title' => $item->artwork_title,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                    'currency' => $item->currency,
                    'formatted_price' => $item->formatted_price,
                    'formatted_subtotal' => $item->formatted_subtotal,
                    'artwork_image' => $item->artwork->primary_image ?? null,
                ];
            }),
        ]);
    }
}
