<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\PaymentProof;
use App\Services\ShippingService;
use App\Services\PaymentService;
use App\Services\EmailService;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected ShippingService $shippingService;
    protected PaymentService $paymentService;
    protected EmailService $emailService;

    public function __construct(ShippingService $shippingService, PaymentService $paymentService, EmailService $emailService)
    {
        $this->shippingService = $shippingService;
        $this->paymentService = $paymentService;
        $this->emailService = $emailService;
    }

    /**
     * Show checkout page.
     */
    public function index()
    {
        $cart = Cart::getOrCreateCart();
        $cart->load('items.artwork');
        
        $shippingMethods = $this->shippingService->getShippingMethods();
        $defaultShippingMethod = 'standard';
        $shippingCost = $this->shippingService->calculateCartShipping($cart, $defaultShippingMethod);
        
        return view('checkout.index', compact('cart', 'shippingMethods', 'defaultShippingMethod', 'shippingCost'));
    }

    /**
     * Process guest checkout.
     */
    public function guest(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255|regex:/^[\p{L}\s\-\'.]+$/u',
            'last_name' => 'required|string|max:255|regex:/^[\p{L}\s\-\'.]+$/u',
            'phone' => 'required|string|min:8|max:20|regex:/^[\+\d\s\-\(\)]+$/',
            'address' => 'required|string|min:5|max:500',
            'city' => 'required|string|max:255|regex:/^[\p{L}\s\-\.]+$/u',
            'state' => 'nullable|string|max:255|regex:/^[\p{L}\s\-\.]+$/u',
            'postal_code' => 'required|string|max:20|regex:/^[\w\s\-]+$/',
            'country' => 'required|string|size:2',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'shipping_method' => 'required|in:standard,express,premium',
            'agree_terms' => 'accepted',
            'order_notes' => 'nullable|string|max:1000',
            'is_gift' => 'nullable|boolean',
            'gift_message' => 'nullable|string|max:500|required_if:is_gift,1',
            'gift_receipt' => 'nullable|boolean',
            'payment_screenshot' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $cart = Cart::getOrCreateCart();
        $cart->load('items.artwork');
        
        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.cart_empty'),
            ], 400);
        }

        // Get payment method
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        // Calculate shipping cost
        $shippingCost = $this->shippingService->calculateCartShipping($cart, $request->shipping_method);
        $totalAmount = $cart->total_amount + $shippingCost;

        // Use database transaction for atomic operations
        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => null, // Guest order
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $this->formatPhoneNumber($request->phone),
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'total_amount' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'subtotal_amount' => $cart->subtotal_amount,
                'currency' => $cart->currency,
                'status' => 'pending',
                'payment_method' => $paymentMethod->code,
                'payment_method_id' => $paymentMethod->id,
                'shipping_method' => $request->shipping_method,
                'order_notes' => is_string($request->order_notes) ? $request->order_notes : '',
                'is_gift' => $request->boolean('is_gift'),
                'gift_message' => is_string($request->gift_message) ? $request->gift_message : '',
                'gift_receipt' => $request->boolean('gift_receipt', true),
            ]);

            // Create order items and decrease stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'artwork_id' => $item->artwork_id,
                    'artwork_title' => $item->artwork_title,
                    'price' => $item->price,
                    'currency' => $item->currency,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ]);
                
                // Decrease artwork stock
                $item->artwork->decreaseStock($item->quantity);
            }

            // Process payment
            $paymentResult = $this->paymentService->processPayment($order, $paymentMethod->code);

            if (!$paymentResult['success']) {
                // Rollback transaction on payment failure
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => $paymentResult['message'],
                ], 400);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->updateTotal();

            // Handle payment screenshot upload for manual payment methods
            if ($request->hasFile('payment_screenshot') && $paymentMethod->requires_manual_verification) {
                $screenshotPath = $request->file('payment_screenshot')->store('payment_screenshots', 'public');

                PaymentProof::create([
                    'order_id' => $order->id,
                    'payment_method_id' => $paymentMethod->id,
                    'screenshot' => $screenshotPath,
                    'status' => 'pending',
                    'notes' => 'Payment screenshot uploaded during checkout',
                ]);
            }

            // Commit transaction
            DB::commit();

            // Send order confirmation email (outside transaction)
            Mail::to($order->email)->send(new OrderConfirmationMail($order));

            // Check if payment requires manual verification (upload)
            $requiresUpload = $paymentMethod->requires_manual_verification;

            return response()->json([
                'success' => true,
                'message' => __('messages.order_placed'),
                'order_id' => $order->id,
                'requires_upload' => $requiresUpload,
                'payment_url' => $requiresUpload ? route('payment.upload', ['order' => $order->id]) : null,
                'redirect' => route('checkout.success', ['order_id' => $order->id]),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => [
                    'email' => $request->email ?? 'guest',
                    'order_total' => $totalAmount ?? 0,
                    'payment_method' => $request->payment_method_id ?? null,
                ],
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.checkout_error'),
            ], 500);
        }
    }

    /**
     * Process authenticated checkout.
     */
    public function authenticated(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|min:8|max:20|regex:/^[\+\d\s\-\(\)]+$/',
            'address' => 'required|string|min:5|max:500',
            'city' => 'required|string|max:255|regex:/^[\p{L}\s\-\.]+$/u',
            'state' => 'nullable|string|max:255|regex:/^[\p{L}\s\-\.]+$/u',
            'postal_code' => 'required|string|max:20|regex:/^[\w\s\-]+$/',
            'country' => 'required|string|size:2',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'shipping_method' => 'required|in:standard,express,premium',
            'agree_terms' => 'accepted',
            'order_notes' => 'nullable|string|max:1000',
            'is_gift' => 'nullable|boolean',
            'gift_message' => 'nullable|string|max:500|required_if:is_gift,1',
            'gift_receipt' => 'nullable|boolean',
            'save_address' => 'nullable|boolean',
            'payment_screenshot' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $cart = Cart::getOrCreateCart();
        $cart->load('items.artwork');
        
        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.cart_empty'),
            ], 400);
        }

        // Get payment method
        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        // Calculate shipping cost
        $shippingCost = $this->shippingService->calculateCartShipping($cart, $request->shipping_method);
        $totalAmount = $cart->total_amount + $shippingCost;

        // Update user's contact info and save address if requested
        $user = Auth::user();
        $updateData = [];
        
        if ($request->phone && empty($user->phone)) {
            $updateData['phone'] = $this->formatPhoneNumber($request->phone);
        }
        
        // Save address to user profile if requested
        if ($request->boolean('save_address')) {
            $updateData['address'] = $request->address;
            $updateData['city'] = $request->city;
            $updateData['state'] = $request->state;
            $updateData['postal_code'] = $request->postal_code;
            $updateData['country'] = $request->country;
        }
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'email' => $user->email,
            'first_name' => $user->first_name ?? $user->name,
            'last_name' => $user->last_name ?? '',
            'phone' => $this->formatPhoneNumber($request->phone),
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'total_amount' => $totalAmount,
            'currency' => $cart->currency,
            'status' => 'pending',
            'payment_method' => $paymentMethod->code,
            'payment_method_id' => $paymentMethod->id,
            'shipping_method' => $request->shipping_method,
            'order_notes' => is_string($request->order_notes) ? $request->order_notes : '',
            'is_gift' => $request->boolean('is_gift'),
            'gift_message' => is_string($request->gift_message) ? $request->gift_message : '',
            'gift_receipt' => $request->boolean('gift_receipt', true),
        ]);

        // Create order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'artwork_id' => $item->artwork_id,
                'artwork_title' => $item->artwork_title,
                'price' => $item->price,
                'currency' => $item->currency,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ]);
            
            // Decrease artwork stock
            $item->artwork->decreaseStock($item->quantity);
        }

        // Process payment
        $paymentResult = $this->paymentService->processPayment($order, $paymentMethod->code);

        if (!$paymentResult['success']) {
            // Rollback on payment failure
            foreach ($cart->items as $item) {
                $item->artwork->increaseStock($item->quantity);
            }
            OrderItem::where('order_id', $order->id)->delete();
            $order->delete();

            return response()->json([
                'success' => false,
                'message' => $paymentResult['message'],
            ], 400);
        }

        // Clear cart
        $cart->items()->delete();
        $cart->updateTotal();

        // Handle payment screenshot upload for manual payment methods
        if ($request->hasFile('payment_screenshot') && $paymentMethod->requires_manual_verification) {
            $screenshotPath = $request->file('payment_screenshot')->store('payment_screenshots', 'public');

            PaymentProof::create([
                'order_id' => $order->id,
                'payment_method_id' => $paymentMethod->id,
                'screenshot' => $screenshotPath,
                'status' => 'pending',
                'notes' => 'Payment screenshot uploaded during checkout',
            ]);
        }

        // Send order confirmation email
        Mail::to($order->email)->send(new OrderConfirmationMail($order));

        // Check if payment requires manual verification (upload)
        $requiresUpload = $paymentMethod->requires_manual_verification;

        return response()->json([
            'success' => true,
            'message' => __('messages.order_placed'),
            'order_id' => $order->id,
            'requires_upload' => $requiresUpload,
            'payment_url' => $requiresUpload ? route('payment.upload', ['order' => $order->id]) : null,
            'redirect' => route('checkout.success', ['order_id' => $order->id]),
        ]);
    }

    /**
     * Show checkout success page.
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = null;

        if ($orderId) {
            $order = Order::with('items.artwork')->find($orderId);
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Format phone number to international format.
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters except +
        $phone = preg_replace('/[^\d\+]/', '', $phone);
        
        // Ensure it starts with + for international format
        if (!str_starts_with($phone, '+')) {
            // If starts with 0, assume local number
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            // Default to Myanmar (+95) if no country code
            $phone = '+95' . $phone;
        }
        
        return $phone;
    }

    /**
     * Update cart item quantity (AJAX endpoint).
     */
    public function updateCartItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $newQuantity = $request->quantity;
        
        // Check stock availability before updating
        $artwork = $cartItem->artwork;
        if (!$artwork->hasStock($newQuantity)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.insufficient_stock', ['count' => $artwork->getAvailableStock()]),
            ], 400);
        }

        // Update quantity
        $cartItem->update([
            'quantity' => $newQuantity,
            'subtotal' => $cartItem->price * $newQuantity,
        ]);

        // Recalculate cart total
        $cart = $cartItem->cart;
        $cart->updateTotal();

        // Recalculate shipping
        $shippingCost = $this->shippingService->calculateCartShipping($cart, $request->shipping_method ?? 'standard');

        return response()->json([
            'success' => true,
            'message' => __('messages.quantity_updated'),
            'cart' => [
                'item_count' => $cart->item_count,
                'total_amount' => $cart->formatted_total,
                'subtotal' => $cart->formatted_subtotal,
                'discount' => $cart->formatted_discount,
            ],
            'item_subtotal' => app(\App\Services\CurrencyService::class)->format($cartItem->subtotal, $cartItem->currency),
            'shipping_cost' => $shippingCost,
        ]);
    }

    /**
     * Remove cart item (AJAX endpoint for checkout page).
     */
    public function removeCartItem(CartItem $cartItem): JsonResponse
    {
        $cart = Cart::getOrCreateCart();
        
        // Verify the cart item belongs to current cart
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json([
                'success' => false,
                'message' => __('messages.item_not_found_cart'),
            ], 403);
        }

        $cartItem->delete();
        $cart->updateTotal();

        // Check if cart is now empty
        $isEmpty = $cart->items()->count() === 0;

        return response()->json([
            'success' => true,
            'message' => __('messages.item_removed_cart'),
            'cart_empty' => $isEmpty,
            'cart' => [
                'item_count' => $cart->item_count,
                'total_amount' => $cart->formatted_total,
                'subtotal' => $cart->formatted_subtotal,
                'discount' => $cart->formatted_discount,
            ],
        ]);
    }
}
