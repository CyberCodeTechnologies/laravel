@extends('layouts.app')

@section('title', __('messages.shopping_cart') . ' - Panchi Gallery')
@section('meta-description', __('messages.cart_meta_description'))
@section('meta-keywords', 'shopping cart, buy Myanmar art, art checkout, purchase artwork, art gallery cart, Panchi Gallery checkout')

@section('content')
<div class="min-h-screen">
<!-- Cart Header -->
<section class="bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('messages.shopping_cart') }}</h1>
                <p class="text-gray-600 mt-1">
                    @if(isset($cart) && $cart->item_count > 0)
                        {{ __('messages.item_count_cart', ['count' => $cart->item_count]) }}
                    @else
                        {{ __('messages.cart_empty') }}
                    @endif
                </p>
            </div>
            <a href="{{ route('public.artworks.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('messages.continue_shopping') }}
            </a>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($cart) && $cart->item_count > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-gray-200">
                        <!-- Cart Header -->
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-gray-900">{{ __('messages.cart_items') }}</h2>
                                <button onclick="clearCart()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                    {{ __('messages.clear_cart') }}
                                </button>
                            </div>
                        </div>
                        
                        <!-- Cart Items List -->
                        <div class="divide-y divide-gray-200">
                            @foreach($cart->items as $item)
                                <div class="p-6">
                                    <div class="flex items-center space-x-4">
                                        <!-- Artwork Image -->
                                        <div class="flex-shrink-0">
                                            <img src="{{ $item->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" 
                                                 alt="{{ $item->artwork_title }}" 
                                                 class="w-24 h-24 object-cover rounded-lg">
                                        </div>
                                        
                                        <!-- Artwork Details -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                                {{ $item->artwork_title }}
                                            </h3>
                                            @if($item->artwork->artist && $item->artwork->artist->slug)
                                                <p class="text-sm text-gray-600 mb-2">
                                                    {{ __('messages.by_artist') }} <a href="{{ route('public.artists.show', $item->artwork->artist->slug) }}" class="text-indigo-600 hover:text-indigo-700">
                                                        {{ $item->artwork->artist->name }}
                                                    </a>
                                                </p>
                                            @endif
                                            <div class="flex flex-wrap gap-2">
                                                @if($item->artwork->category)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                                        {{ $item->artwork->category->name }}
                                                    </span>
                                                @endif
                                                @if($item->artwork->medium)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                                        {{ $item->artwork->medium }}
                                                    </span>
                                                @endif
                                                @if($item->artwork->dimensions)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">
                                                        {{ $item->artwork->dimensions }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Price and Actions -->
                                        <div class="flex flex-col items-end space-y-2">
                                            <div class="text-lg font-bold text-gray-900">
                                                {{ $item->formatted_price }}
                                            </div>
                                            <!-- Quantity Selector -->
                                            <div class="flex items-center border border-gray-300 rounded-lg">
                                                <button onclick="decreaseCartItemQuantity({{ $item->id }})" 
                                                        class="px-3 py-1 hover:bg-gray-100 transition-colors">
                                                    −
                                                </button>
                                                <span id="quantity-{{ $item->id }}" class="px-3 py-1 min-w-[40px] text-center">
                                                    {{ $item->quantity }}
                                                </span>
                                                <button onclick="increaseCartItemQuantity({{ $item->id }})" 
                                                        class="px-3 py-1 hover:bg-gray-100 transition-colors">
                                                    +
                                                </button>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <button onclick="removeFromCart({{ $item->id }})" 
                                                        class="text-red-600 hover:text-red-700 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <button onclick="moveToWishlist({{ $item->artwork_id }})" 
                                                        class="text-gray-600 hover:text-gray-700 transition-colors">
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Info -->
                                    @if($item->artwork->is_verified)
                                        <div class="mt-4 flex items-center text-sm text-green-600">
                                            <i class="fas fa-certificate mr-2"></i>
                                            {{ __('messages.includes_coa') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('messages.order_summary') }}</h2>
                        
                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>{{ __('messages.subtotal_items', ['count' => $cart->item_count]) }}</span>
                                <span id="cart-subtotal">{{ $cart->formatted_subtotal ?? $cart->formatted_total }}</span>
                            </div>
                            <div id="discount-row" class="flex justify-between text-green-600 {{ $cart->discount_amount > 0 ? '' : 'hidden' }}">
                                <span>{{ __('messages.discount') }} {{ $cart->promo_code ? '(' . $cart->promo_code . ')' : '' }}</span>
                                <span id="cart-discount">-{{ $cart->formatted_discount ?? '0.00' }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>{{ __('messages.platform_fee') }} ({{ \App\Models\Transaction::getPlatformFeePercentage() }}%)</span>
                                <span>{{ number_format(($cart->total_amount + ($cart->discount_amount ?? 0)) * (\App\Models\Transaction::getPlatformFeePercentage() / 100), 2) }} {{ $cart->currency }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>{{ __('messages.shipping') }}</span>
                                <span>{{ __('messages.shipping_calculated_checkout') }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-semibold text-gray-900">
                                    <span>{{ __('messages.total') }}</span>
                                    <span class="cart-total">{{ $cart->formatted_total }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Promo Code -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.promo_code') }}</label>
                            <div class="flex space-x-2">
                                <input type="text" 
                                       id="promo-code"
                                       placeholder="{{ __('messages.enter_promo_code') }}"
                                       value="{{ $cart->promo_code ?? '' }}"
                                       {{ $cart->promo_code ? 'disabled' : '' }}
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent uppercase">
                                <button onclick="{{ $cart->promo_code ? 'removePromoCode()' : 'applyPromoCode()' }}" 
                                        class="px-4 py-2 {{ $cart->promo_code ? 'bg-red-100 text-red-600' : 'bg-gray-200 text-gray-700' }} rounded-lg hover:opacity-80 transition-colors">
                                    {{ $cart->promo_code ? __('messages.remove') : __('messages.apply') }}
                                </button>
                            </div>
                            @if($cart->promo_code)
                                <p class="text-xs text-green-600 mt-1">{{ __('messages.promo_applied_success') }}</p>
                            @endif
                        </div>
                        
                        <!-- Checkout Button -->
                        <button onclick="proceedToCheckout()" 
                                class="w-full bg-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-lock mr-2"></i>
                            {{ __('messages.proceed_to_checkout') }}
                        </button>
                        
                        <!-- Security Info -->
                        <div class="mt-4 text-center text-sm text-gray-600">
                            <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                            {{ __('messages.secure_checkout_ssl') }}
                        </div>
                        
                        <!-- Accepted Payments -->
                        <div class="mt-6">
                            <p class="text-sm text-gray-600 mb-2">{{ __('messages.accepted_payment_methods') }}</p>
                            <div class="flex space-x-2">
                                <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fab fa-cc-visa text-blue-600"></i>
                                </div>
                                <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fab fa-cc-mastercard text-red-600"></i>
                                </div>
                                <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fab fa-cc-amex text-blue-500"></i>
                                </div>
                                <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fab fa-paypal text-blue-700"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                    <i class="fas fa-shopping-cart text-gray-400 text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('messages.cart_empty_title') }}</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    {{ __('messages.cart_empty_description') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.artworks.index') }}" 
                       class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                        {{ __('messages.browse_artworks') }}
                    </a>
                    <a href="{{ route('public.artists.index') }}" 
                       class="border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                        {{ __('messages.discover_artists') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
</div>
@endsection

@push('scripts')
<script>
    // Decrease cart item quantity
    function decreaseCartItemQuantity(cartItemId) {
        const quantityElement = document.getElementById(`quantity-${cartItemId}`);
        let quantity = parseInt(quantityElement.textContent);
        
        if (quantity > 1) {
            updateCartItemQuantity(cartItemId, quantity - 1);
        }
    }

    // Increase cart item quantity
    function increaseCartItemQuantity(cartItemId) {
        const quantityElement = document.getElementById(`quantity-${cartItemId}`);
        let quantity = parseInt(quantityElement.textContent);
        
        if (quantity < 10) {
            updateCartItemQuantity(cartItemId, quantity + 1);
        }
    }

    // Update cart item quantity
    function updateCartItemQuantity(cartItemId, newQuantity) {
        fetch(`{{ url('/cart/update') }}/${cartItemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Quantity updated', 'success');
                location.reload();
            } else {
                showToast(data.message || 'Failed to update quantity', 'error');
            }
        })
        .catch(error => {
            console.error('Error updating quantity:', error);
            showToast('Failed to update quantity. Please try again.', 'error');
        });
    }

    // Remove item from cart
    function removeFromCart(cartItemId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            fetch(`{{ url('/cart/remove') }}/${cartItemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Item removed from cart', 'success');
                    updateCartCount();
                    location.reload();
                } else {
                    showToast(data.message || 'Failed to remove item', 'error');
                }
            })
            .catch(error => {
                console.error('Error removing item:', error);
                showToast('Failed to remove item. Please try again.', 'error');
            });
        }
    }
    
    // Clear cart
    function clearCart() {
        if (confirm('Are you sure you want to clear your entire cart?')) {
            fetch('{{ url('/cart/clear') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Cart cleared successfully', 'success');
                    updateCartCount();
                    location.reload();
                } else {
                    showToast(data.message || 'Failed to clear cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error clearing cart:', error);
                showToast('Failed to clear cart. Please try again.', 'error');
            });
        }
    }
    
    // Move to wishlist
    function moveToWishlist(artworkId) {
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ artwork_id: artworkId })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    return Promise.reject('Authentication required');
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Artwork moved to wishlist', 'success');
                updateCartCount();
                location.reload();
            } else {
                showToast(data.message || 'Failed to move to wishlist', 'info');
            }
        })
        .catch(error => {
            if (error === 'Authentication required') return;
            console.error('Error moving to wishlist:', error);
            showToast('Failed to move to wishlist. Please try again.', 'error');
        });
    }
    
    // Apply promo code
    function applyPromoCode() {
        const code = document.getElementById('promo-code').value.trim();
        if (!code) {
            showToast('Please enter a promo code', 'warning');
            return;
        }

        fetch('{{ route('cart.promo.apply') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Promo code applied successfully', 'success');
                location.reload();
            } else {
                showToast(data.message || 'Failed to apply promo code', 'error');
            }
        })
        .catch(error => {
            console.error('Error applying promo code:', error);
            showToast('Failed to apply promo code. Please try again.', 'error');
        });
    }
    
    // Remove promo code
    function removePromoCode() {
        fetch('{{ route('cart.promo.remove') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Promo code removed', 'success');
                location.reload();
            } else {
                showToast(data.message || 'Failed to remove promo code', 'error');
            }
        })
        .catch(error => {
            console.error('Error removing promo code:', error);
            showToast('Failed to remove promo code. Please try again.', 'error');
        });
    }

    // Proceed to checkout
    function proceedToCheckout() {
        window.location.href = '{{ route("checkout.index") }}';
    }
</script>
@endpush
