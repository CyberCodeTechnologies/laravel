@extends('layouts.app')

@section('title', __('messages.checkout') . ' - ' . config('app.name'))
@section('meta-keywords', 'checkout, buy art online, purchase artwork, art payment, art shipping, Myanmar art checkout, Panchi Gallery checkout, secure payment')

@section('content')
@php
$currencyService = app(\App\Services\CurrencyService::class);
$feePercentage = \App\Models\Transaction::getPlatformFeePercentage();
$feeDecimal = $feePercentage / 100;
$feeMultiplier = 1 + $feeDecimal;
$currentCurrency = $cart->currency ?? session('currency', 'USD');
$currencySymbol = $currencyService->getSymbol($currentCurrency);
$hasPhysicalItems = $cart->items->contains(fn($item) => !$item->artwork->is_digital);
$hasDigitalItems = $cart->items->contains(fn($item) => $item->artwork->is_digital);
$freeShippingThreshold = 500;
$isFreeShippingEligible = $cart->total_amount >= $freeShippingThreshold && $hasPhysicalItems;
$hasPromoCode = $cart->promo_code_id !== null;
$subtotalAfterDiscount = $cart->subtotal_amount - ($cart->discount_amount ?? 0);
@endphp

<!-- Enhanced Progress Steps -->
<section class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-center">
            <div class="w-full max-w-3xl">
                <div class="relative">
                    <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-200 -translate-y-1/2"></div>
                    <div class="absolute top-1/2 left-0 h-0.5 bg-black -translate-y-1/2 transition-all duration-500" id="progress-line" style="width: 0%"></div>
                    <div class="relative flex justify-between">
                        @foreach([
                            ['key' => 'shipping', 'label' => __('messages.shipping'), 'icon' => 'fa-truck'],
                            ['key' => 'payment', 'label' => __('messages.payment'), 'icon' => 'fa-credit-card'],
                            ['key' => 'review', 'label' => __('messages.review'), 'icon' => 'fa-check-circle']
                        ] as $index => $step)
                        <div class="flex flex-col items-center step-item" data-step="{{ $index + 1 }}">
                            <div class="step-circle w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 {{ $index === 0 ? 'bg-black text-white ring-4 ring-gray-100' : 'bg-white text-gray-400 border-2 border-gray-300' }}" id="step-circle-{{ $index + 1 }}">
                                <i class="fas {{ $step['icon'] }}"></i>
                            </div>
                            <span class="mt-2 text-xs font-medium {{ $index === 0 ? 'text-gray-900' : 'text-gray-500' }}" id="step-label-{{ $index + 1 }}">{{ $step['label'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-8 bg-gradient-to-b from-gray-50 to-white min-h-screen">
    @if($cart && $cart->items && $cart->items->count() > 0)
        <form action="{{ auth()->check() ? route('checkout.process') : route('checkout.guest') }}" method="POST" id="checkout-form" class="checkout-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="current_step" id="current-step-input" value="1">
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                    
                    <div class="xl:col-span-8 space-y-6">
                        
                        <!-- Global Validation Errors -->
                        <div id="global-errors" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-red-800">{{ __('messages.please_fix_errors') }}</h3>
                                    <ul class="mt-2 text-sm text-red-700 space-y-1" id="global-error-list"></ul>
                                </div>
                            </div>
                        </div>

                        <!-- Step 1: Contact & Shipping -->
                        <div class="checkout-step" id="step-1">
                            
                            <!-- Contact Information -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm mr-3"><i class="fas fa-user"></i></span>
                                        {{ __('messages.contact_information') }}
                                    </h2>
                                </div>
                                <div class="p-6">
                                    @if(!auth()->check())
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.email_address') }} <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-envelope text-sm"></i></span>
                                                    <input type="email" name="email" id="email" required value="{{ old('email') }}" class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all" placeholder="your@email.com">
                                                </div>
                                                <p class="mt-1 text-xs text-red-600 hidden" id="error-email"></p>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.phone_number') }} <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-phone text-sm"></i></span>
                                                    <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}" class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all" placeholder="+95 9 XXX XXX XXX">
                                                </div>
                                                <p class="mt-1 text-xs text-red-600 hidden" id="error-phone"></p>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.first_name') }} <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-user text-sm"></i></span>
                                                    <input type="text" name="first_name" id="first_name" required value="{{ old('first_name') }}" class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all" placeholder="John">
                                                </div>
                                                <p class="mt-1 text-xs text-red-600 hidden" id="error-first_name"></p>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.last_name') }} <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-user text-sm"></i></span>
                                                    <input type="text" name="last_name" id="last_name" required value="{{ old('last_name') }}" class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all" placeholder="Doe">
                                                </div>
                                                <p class="mt-1 text-xs text-red-600 hidden" id="error-last_name"></p>
                                            </div>
                                        </div>
                                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                            <p class="text-sm text-blue-700"><i class="fas fa-info-circle mr-2"></i>{{ __('messages.already_have_account') }} <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" class="font-semibold underline hover:text-blue-800">{{ __('messages.login_for_faster_checkout') }}</a></p>
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.email_address') }}</label>
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-envelope text-sm"></i></span>
                                                    <input type="email" value="{{ auth()->user()->email }}" disabled class="pl-10 w-full px-3 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed">
                                                </div>
                                                <p class="mt-1 text-xs text-gray-500"><i class="fas fa-lock mr-1"></i>{{ __('messages.secure_account_email') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('messages.phone_number') }} <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fas fa-phone text-sm"></i></span>
                                                    <input type="tel" name="phone" id="phone" required value="{{ old('phone', auth()->user()->phone ?? '') }}" class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all" placeholder="+95 9 XXX XXX XXX">
                                                </div>
                                                <p class="mt-1 text-xs text-red-600 hidden" id="error-phone"></p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Shipping Address Card -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm mr-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        {{ __('messages.shipping_address') }}
                                    </h2>
                                </div>
                                
                                <div class="p-6 space-y-5">
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            {{ __('messages.street_address') }} <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <i class="fas fa-home text-sm"></i>
                                            </span>
                                            <input type="text" 
                                                   name="address" 
                                                   id="address"
                                                   required
                                                   value="{{ old('address', auth()->user()->address ?? '') }}"
                                                   class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all @error('address') border-red-500 @enderror"
                                                   placeholder="123 Main Street, Apartment 4B">
                                        </div>
                                        <p class="mt-1 text-xs text-red-600 hidden" id="error-address"></p>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                {{ __('messages.city') }} <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                    <i class="fas fa-city text-sm"></i>
                                                </span>
                                                <input type="text" 
                                                       name="city" 
                                                       id="city"
                                                       required
                                                       value="{{ old('city') }}"
                                                       class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all @error('city') border-red-500 @enderror"
                                                       placeholder="Yangon">
                                            </div>
                                            <p class="mt-1 text-xs text-red-600 hidden" id="error-city"></p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                {{ __('messages.state_province') }}
                                            </label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                    <i class="fas fa-map text-sm"></i>
                                                </span>
                                                <input type="text" 
                                                       name="state" 
                                                       id="state"
                                                       value="{{ old('state') }}"
                                                       class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all @error('state') border-red-500 @enderror"
                                                       placeholder="Yangon Region">
                                            </div>
                                            <p class="mt-1 text-xs text-red-600 hidden" id="error-state"></p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                {{ __('messages.zip_postal') }} <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                    <i class="fas fa-mail-bulk text-sm"></i>
                                                </span>
                                                <input type="text" 
                                                       name="postal_code" 
                                                       id="postal_code"
                                                       required
                                                       value="{{ old('postal_code') }}"
                                                       class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all @error('postal_code') border-red-500 @enderror"
                                                       placeholder="11181">
                                            </div>
                                            <p class="mt-1 text-xs text-red-600 hidden" id="error-postal_code"></p>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                {{ __('messages.country') }} <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                    <i class="fas fa-globe text-sm"></i>
                                                </span>
                                                <select name="country" 
                                                        id="country"
                                                        required
                                                        class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all @error('country') border-red-500 @enderror">
                                                    <option value="">{{ __('messages.select_country') }}</option>
                                                    <option value="MM" {{ old('country', auth()->user()->country ?? '') == 'MM' ? 'selected' : '' }}>{{ __('messages.myanmar_country') }} (Myanmar)</option>
                                                    <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>{{ __('messages.united_states') }} (USA)</option>
                                                    <option value="GB" {{ old('country') == 'GB' ? 'selected' : '' }}>{{ __('messages.united_kingdom') }} (UK)</option>
                                                    <option value="SG" {{ old('country') == 'SG' ? 'selected' : '' }}>{{ __('messages.singapore') }} (Singapore)</option>
                                                    <option value="TH" {{ old('country') == 'TH' ? 'selected' : '' }}>{{ __('messages.thailand') }} (Thailand)</option>
                                                    <option value="JP" {{ old('country') == 'JP' ? 'selected' : '' }}>{{ __('messages.japan') ?? 'Japan' }}</option>
                                                    <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>{{ __('messages.australia') ?? 'Australia' }}</option>
                                                </select>
                                            </div>
                                            <p class="mt-1 text-xs text-red-600 hidden" id="error-country"></p>
                                        </div>
                                    </div>
                                    
                                    @if(auth()->check())
                                    <div class="flex items-center mt-4">
                                        <input type="checkbox" 
                                               name="save_address" 
                                               id="save_address"
                                               value="1"
                                               {{ old('save_address') ? 'checked' : '' }}
                                               class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black">
                                        <label for="save_address" class="ml-2 text-sm text-gray-600">
                                            {{ __('messages.save_address_future') }}
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Shipping Method Card -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm mr-3">
                                            <i class="fas fa-shipping-fast"></i>
                                        </span>
                                        {{ __('messages.shipping_method') }}
                                    </h2>
                                </div>
                                
                                <div class="p-6">
                                    @if(!$hasPhysicalItems)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-download text-green-600 mr-3 text-xl"></i>
                                                <div>
                                                    <h4 class="font-semibold text-green-800">{{ __('messages.digital_delivery') }}</h4>
                                                    <p class="text-sm text-green-700">
                                                        {{ __('messages.digital_items_notice') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="shipping_method" value="digital">
                                    @else
                                        @if($isFreeShippingEligible)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-5">
                                            <div class="flex items-center">
                                                <i class="fas fa-gift text-green-600 mr-3 text-xl"></i>
                                                <div>
                                                    <h4 class="font-semibold text-green-800">{{ __('messages.free_shipping_unlocked') }}</h4>
                                                    <p class="text-sm text-green-700">
                                                        {{ __('messages.free_shipping_message', ['amount' => $currencySymbol . number_format($freeShippingThreshold, 2)]) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="space-y-3" id="shipping-methods">
                                            @foreach($shippingMethods as $method => $details)
                                            @php
                                            $isFree = $isFreeShippingEligible && $method === 'standard';
                                            $actualCost = $isFree ? 0 : $details['base_rate'];
                                            @endphp
                                            <label class="shipping-option relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $method === $defaultShippingMethod ? 'border-black bg-gray-50' : 'border-gray-200 hover:border-gray-300' }}"
                                                   data-method="{{ $method }}"
                                                   data-cost="{{ $actualCost }}">
                                                <input type="radio" 
                                                       name="shipping_method" 
                                                       value="{{ $method }}" 
                                                       {{ $method === $defaultShippingMethod ? 'checked' : '' }}
                                                       class="shipping-radio sr-only">
                                                <div class="flex-1 flex items-center">
                                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-4 flex-shrink-0">
                                                        @if($details['icon'] === 'truck')
                                                            <i class="fas fa-truck text-gray-600 text-lg"></i>
                                                        @elseif($details['icon'] === 'bolt')
                                                            <i class="fas fa-bolt text-gray-600 text-lg"></i>
                                                        @elseif($details['icon'] === 'star')
                                                            <i class="fas fa-star text-gray-600 text-lg"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <h3 class="font-semibold text-gray-900">{{ $details['name'] }}</h3>
                                                                <p class="text-sm text-gray-500">{{ $details['description'] }}</p>
                                                            </div>
                                                            <div class="text-right">
                                                                @if($isFree)
                                                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full mb-1">FREE</span>
                                                                    <p class="font-bold text-gray-900">{{ $currencySymbol }}0.00</p>
                                                                @else
                                                                    <p class="font-bold text-gray-900">{{ $currencySymbol }}{{ number_format($actualCost, 2) }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-shrink-0">
                                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors {{ $method === $defaultShippingMethod ? 'border-black bg-black' : 'border-gray-300' }}"
                                                         id="shipping-check-{{ $method }}">
                                                        @if($method === $defaultShippingMethod)
                                                            <i class="fas fa-check text-white text-xs"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Order Notes Card -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm mr-3">
                                            <i class="fas fa-sticky-note"></i>
                                        </span>
                                        {{ __('messages.order_notes') }} ({{ __('messages.optional') }})
                                    </h2>
                                </div>
                                
                                <div class="p-6">
                                    <div class="form-group">
                                        <textarea name="order_notes" 
                                                  id="order_notes"
                                                  rows="3"
                                                  class="w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent focus:bg-white transition-all resize-none"
                                                  placeholder="{{ __('messages.order_notes_placeholder') }}"></textarea>
                                    </div>
                                    
                                    <!-- Gift Option -->
                                    <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex items-start">
                                            <input type="checkbox" 
                                                   name="is_gift" 
                                                   id="is_gift"
                                                   value="1"
                                                   {{ old('is_gift') ? 'checked' : '' }}
                                                   class="mt-1 w-4 h-4 text-black border-gray-300 rounded focus:ring-black"
                                                   onchange="toggleGiftMessage(this)">
                                            <div class="ml-3 flex-1">
                                                <label for="is_gift" class="font-medium text-gray-900 cursor-pointer">
                                                    <i class="fas fa-gift mr-1"></i>
                                                    {{ __('messages.this_is_gift') }}
                                                </label>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ __('messages.gift_note') }}
                                                </p>
                                                
                                                <!-- Gift Message (hidden by default) -->
                                                <div id="gift-message-container" class="mt-3 {{ old('is_gift') ? '' : 'hidden' }}">
                                                    <textarea name="gift_message" 
                                                              id="gift_message"
                                                              rows="2"
                                                              maxlength="500"
                                                              class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent resize-none text-sm"
                                                              placeholder="{{ __('messages.gift_message_placeholder') }}"
                                                              oninput="updateCharCount(this, 'gift-char-count')">{{ is_array(old('gift_message')) ? '' : old('gift_message') }}</textarea>
                                                    <div class="flex justify-between mt-1">
                                                        <span class="text-xs text-gray-500">{{ __('messages.max_500_chars') }}</span>
                                                        <span id="gift-char-count" class="text-xs text-gray-500">0/500</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Gift Receipt Option -->
                                                <div class="mt-3 flex items-center">
                                                    <input type="checkbox" 
                                                           name="gift_receipt" 
                                                           id="gift_receipt"
                                                           value="1"
                                                           checked
                                                           class="w-4 h-4 text-black border-gray-300 rounded focus:ring-black">
                                                    <label for="gift_receipt" class="ml-2 text-sm text-gray-600 cursor-pointer">
                                                        {{ __('messages.include_gift_receipt') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Step Navigation -->
                            <div class="flex justify-end pt-4">
                                <button type="button" 
                                        class="btn-next-step bg-black text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-all flex items-center shadow-lg hover:shadow-xl"
                                        data-next="2">
                                    {{ __('messages.continue_to_payment') ?? 'Continue to Payment' }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Payment Method -->
                        <div class="checkout-step hidden" id="step-2">
                            
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm mr-3">
                                            <i class="fas fa-credit-card"></i>
                                        </span>
                                        {{ __('messages.payment_method') }}
                                    </h2>
                                </div>
                                
                                <div class="p-6">
                                    @php
                                        $paymentMethods = \App\Models\PaymentMethod::active()->get();
                                    @endphp
                                    
                                    @if($paymentMethods->isEmpty())
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <p class="text-yellow-800">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                {{ __('messages.no_payment_methods') }}
                                            </p>
                                        </div>
                                    @else
                                        <div class="space-y-3">
                                            @foreach($paymentMethods as $index => $method)
                                            @php
                                            $methodIcons = [
                                                'stripe' => ['fab fa-cc-visa', 'fab fa-cc-mastercard', 'fab fa-cc-amex'],
                                                'paypal' => ['fab fa-paypal'],
                                                'bank_transfer' => ['fas fa-university'],
                                                'mobile_payment' => ['fas fa-mobile-alt'],
                                                'kbz_pay' => ['fas fa-wallet'],
                                                'wave_pay' => ['fas fa-wallet'],
                                                'cb_pay' => ['fas fa-wallet'],
                                                'aya_pay' => ['fas fa-wallet'],
                                                'default' => ['fas fa-money-bill']
                                            ];
                                            $icons = $methodIcons[$method->code] ?? $methodIcons[$method->type] ?? $methodIcons['default'];
                                            @endphp
                                            <label class="payment-option payment-method-card relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $index === 0 ? 'border-black bg-gray-50' : 'border-gray-200 hover:border-gray-300' }}"
                                                   data-method-id="{{ $method->id }}"
                                                   data-requires-upload="{{ $method->requires_manual_verification ? 'true' : 'false' }}"
                                                   data-method-code="{{ $method->code }}"
                                                   data-type="{{ $method->type }}"
                                                   data-instructions="{{ $method->instructions }}">
                                                <input type="radio"
                                                       name="payment_method_id"
                                                       value="{{ $method->id }}"
                                                       {{ $index === 0 ? 'checked' : '' }}
                                                       class="payment-radio payment-method-radio sr-only"
                                                       data-requires-upload="{{ $method->requires_manual_verification ? 'true' : 'false' }}"
                                                       data-method-code="{{ $method->code }}"
                                                       data-method-name="{{ $method->name }}">
                                                <div class="flex-1 flex items-center">
                                                    <div class="w-14 h-14 rounded-lg bg-white border border-gray-200 flex items-center justify-center mr-4 flex-shrink-0 shadow-sm">
                                                        @foreach($icons as $icon)
                                                            <i class="{{ $icon }} {{ $method->type === 'stripe' ? 'text-blue-600 text-2xl mx-0.5' : ($method->type === 'paypal' ? 'text-blue-700 text-3xl' : 'text-gray-600 text-2xl') }}"></i>
                                                        @endforeach
                                                    </div>
                                                    <div class="flex-1">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <h3 class="font-semibold text-gray-900">{{ $method->name }}</h3>
                                                                @if($method->requires_manual_verification)
                                                                    <div class="flex items-center mt-1">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                            <i class="fas fa-clock mr-1"></i>
                                                                            {{ __('messages.manual_verification') }}
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-sm text-gray-500 mt-1">
                                                                        {{ __('messages.manual_payment_note') }}
                                                                    </p>
                                                                @else
                                                                    <div class="flex items-center mt-1">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                            <i class="fas fa-bolt mr-1"></i>
                                                                            {{ __('messages.instant_processing') }}
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-sm text-gray-500 mt-1">
                                                                        {{ __('messages.secure_payment_note') }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-4 flex-shrink-0">
                                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors {{ $index === 0 ? 'border-black bg-black' : 'border-gray-300' }}"
                                                         id="payment-check-{{ $method->id }}">
                                                        @if($index === 0)
                                                            <i class="fas fa-check text-white text-xs"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                            </label>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Dynamic Payment Instructions -->
                                        <div id="payment-instructions-container" class="mt-4 hidden">
                                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                                                <div class="flex">
                                                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                                                    <div>
                                                        <h4 class="text-sm font-semibold text-blue-800 mb-1" id="payment-instructions-title"></h4>
                                                        <p class="text-sm text-blue-700 whitespace-pre-line" id="payment-instructions-text"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment Screenshot Upload for Myanmar Payment Methods -->
                                        <div id="payment-screenshot-container" class="mt-4 hidden">
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                <div class="flex items-start mb-3">
                                                    <i class="fas fa-upload text-yellow-600 mt-1 mr-3"></i>
                                                    <div>
                                                        <h4 class="text-sm font-semibold text-yellow-800">{{ __('messages.upload_payment_screenshot') }}</h4>
                                                        <p class="text-sm text-yellow-700 mt-1">
                                                            {{ __('messages.screenshot_upload_note') }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="mt-3">
                                                    <div id="screenshot-upload-area" class="border-2 border-dashed border-yellow-300 rounded-lg p-6 text-center cursor-pointer hover:bg-yellow-100 transition-colors relative">
                                                        <input type="file"
                                                               name="payment_screenshot"
                                                               id="payment_screenshot"
                                                               accept="image/jpeg,image/png,image/jpg,image/webp"
                                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                               onchange="handleScreenshotUpload(this)">

                                                        <div id="screenshot-placeholder" class="pointer-events-none">
                                                            <i class="fas fa-cloud-upload-alt text-3xl text-yellow-500 mb-2"></i>
                                                            <p class="text-sm text-yellow-700 font-medium">{{ __('messages.click_or_drag_screenshot') }}</p>
                                                            <p class="text-xs text-yellow-600 mt-1">{{ __('messages.supported_formats') }}</p>
                                                        </div>

                                                        <div id="screenshot-preview" class="hidden pointer-events-none">
                                                            <img id="screenshot-preview-img" src="" alt="Payment Screenshot Preview" class="max-h-48 mx-auto rounded-lg shadow-sm">
                                                            <p class="text-sm text-green-700 mt-2 font-medium">
                                                                <i class="fas fa-check-circle mr-1"></i>
                                                                <span id="screenshot-filename"></span>
                                                            </p>
                                                            <button type="button"
                                                                    onclick="removeScreenshot(event)"
                                                                    class="mt-2 text-xs text-red-600 hover:text-red-800 underline pointer-events-auto relative z-20">
                                                                <i class="fas fa-trash-alt mr-1"></i>{{ __('messages.remove_screenshot') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        {{ __('messages.screenshot_optional_note') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Promo Code Section -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm mr-3">
                                            <i class="fas fa-tag"></i>
                                        </span>
                                        {{ __('messages.promo_code') }}
                                    </h2>
                                </div>
                                
                                <div class="p-6">
                                    @if($hasPromoCode)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-check-circle text-green-600 mr-3 text-xl"></i>
                                                <div>
                                                    <p class="font-semibold text-green-800">{{ $cart->promo_code }}</p>
                                                    <p class="text-sm text-green-600">
                                                        {{ __('messages.saved_amount', ['amount' => $cart->formatted_discount]) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <button type="button" 
                                                    id="remove-promo-btn"
                                                    class="text-red-600 hover:text-red-800 font-medium text-sm">
                                                <i class="fas fa-times mr-1"></i>
                                                {{ __('messages.remove') }}
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex gap-3">
                                            <div class="flex-1 relative">
                                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                    <i class="fas fa-ticket-alt text-sm"></i>
                                                </span>
                                                <input type="text" 
                                                       id="promo-code-input"
                                                       class="pl-10 w-full px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent uppercase"
                                                       placeholder="{{ __('messages.enter_promo_code') }}">
                                            </div>
                                            <button type="button" 
                                                    id="apply-promo-btn"
                                                    class="px-6 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                                                {{ __('messages.apply') }}
                                            </button>
                                        </div>
                                        <p class="mt-2 text-sm text-red-600 hidden" id="promo-error"></p>
                                        <p class="mt-2 text-sm text-green-600 hidden" id="promo-success"></p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Step Navigation -->
                            <div class="flex justify-between pt-4">
                                <button type="button" 
                                        class="btn-prev-step px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all flex items-center"
                                        data-prev="1">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    {{ __('messages.back_to_shipping') ?? 'Back to Shipping' }}
                                </button>
                                <button type="button" 
                                        class="btn-next-step bg-black text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-all flex items-center shadow-lg hover:shadow-xl"
                                        data-next="3">
                                    {{ __('messages.review_order') ?? 'Review Order' }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Review & Confirm -->
                        <div class="checkout-step hidden" id="step-3">
                            
                            <!-- Order Review -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <span class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center text-sm mr-3">
                                            <i class="fas fa-clipboard-check"></i>
                                        </span>
                                        {{ __('messages.review_your_order') }}
                                    </h2>
                                </div>
                                
                                <div class="p-6">
                                    <!-- Review Sections -->
                                    <div class="space-y-6">
                                        
                                        <!-- Contact Summary -->
                                        <div class="review-section">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="font-semibold text-gray-900 flex items-center">
                                                    <i class="fas fa-user text-gray-400 mr-2"></i>
                                                    {{ __('messages.contact_info') }}
                                                </h3>
                                                <button type="button" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" onclick="goToStep(1)">
                                                    <i class="fas fa-pencil-alt mr-1"></i>
                                                    {{ __('messages.edit') }}
                                                </button>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700" id="review-contact">
                                                <p><span class="font-medium">{{ __('messages.email') }}:</span> <span id="review-email">{{ auth()->check() ? auth()->user()->email : '' }}</span></p>
                                                <p class="mt-1"><span class="font-medium">{{ __('messages.phone') }}:</span> <span id="review-phone"></span></p>
                                                @if(!auth()->check())
                                                <p class="mt-1"><span class="font-medium">{{ __('messages.name') }}:</span> <span id="review-name"></span></p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Shipping Summary -->
                                        <div class="review-section">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="font-semibold text-gray-900 flex items-center">
                                                    <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                                    {{ __('messages.shipping_address') }}
                                                </h3>
                                                <button type="button" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" onclick="goToStep(1)">
                                                    <i class="fas fa-pencil-alt mr-1"></i>
                                                    {{ __('messages.edit') }}
                                                </button>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700" id="review-shipping">
                                                <p id="review-address-line"></p>
                                                <p class="mt-1" id="review-city-line"></p>
                                                <p class="mt-1" id="review-country-line"></p>
                                            </div>
                                        </div>
                                        
                                        <!-- Shipping Method Summary -->
                                        @if($hasPhysicalItems)
                                        <div class="review-section">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="font-semibold text-gray-900 flex items-center">
                                                    <i class="fas fa-shipping-fast text-gray-400 mr-2"></i>
                                                    {{ __('messages.shipping_method') }}
                                                </h3>
                                                <button type="button" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" onclick="goToStep(1)">
                                                    <i class="fas fa-pencil-alt mr-1"></i>
                                                    {{ __('messages.edit') }}
                                                </button>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-4" id="review-shipping-method">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-truck text-gray-400 mr-3"></i>
                                                        <span class="font-medium text-gray-900" id="review-shipping-name"></span>
                                                    </div>
                                                    <span class="font-semibold text-gray-900" id="review-shipping-cost"></span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <!-- Payment Method Summary -->
                                        <div class="review-section">
                                            <div class="flex items-center justify-between mb-3">
                                                <h3 class="font-semibold text-gray-900 flex items-center">
                                                    <i class="fas fa-credit-card text-gray-400 mr-2"></i>
                                                    {{ __('messages.payment_method') }}
                                                </h3>
                                                <button type="button" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" onclick="goToStep(2)">
                                                    <i class="fas fa-pencil-alt mr-1"></i>
                                                    {{ __('messages.edit') }}
                                                </button>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-4" id="review-payment">
                                                <div class="flex items-center">
                                                    <i class="fas fa-credit-card text-gray-400 mr-3"></i>
                                                    <span class="font-medium text-gray-900" id="review-payment-name"></span>
                                                </div>
                                                <p class="mt-2 text-sm text-gray-500" id="review-payment-note"></p>
                                            </div>
                                        </div>
                                        
                                        <!-- Order Notes -->
                                        <div class="review-section" id="review-notes-section" style="display: none;">
                                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                                <i class="fas fa-sticky-note text-gray-400 mr-2"></i>
                                                {{ __('messages.order_notes') }}
                                            </h3>
                                            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700" id="review-notes"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Terms and Conditions -->
                                    <div class="mt-8 pt-6 border-t border-gray-200">
                                        <label class="flex items-start cursor-pointer">
                                            <input type="checkbox" 
                                                   name="agree_terms" 
                                                   id="agree_terms"
                                                   required 
                                                   class="mt-1 w-5 h-5 text-black border-gray-300 rounded focus:ring-black">
                                            <span class="ml-3 text-sm text-gray-600">
                                                {{ __('messages.i_agree_to') }}
                                                <a href="{{ route('terms') }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 underline">{{ __('messages.terms_of_service_link') }}</a>
                                                {{ __('messages.and') }}
                                                <a href="{{ route('privacy') }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 underline">{{ __('messages.privacy_policy_link') }}</a>.
                                                {{ __('messages.payment_terms_notice') }}
                                            </span>
                                        </label>
                                        <p class="mt-2 text-xs text-red-600 hidden" id="error-agree_terms">
                                            {{ __('messages.please_accept_terms') }}
                                        </p>
                                    </div>
                                    
                                    <!-- Final Total -->
                                    <div class="mt-6 bg-gray-900 text-white rounded-xl p-6">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-gray-400 text-sm">{{ __('messages.total_amount') }}</p>
                                                <p class="text-3xl font-bold" id="final-total"></p>
                                                <p class="text-gray-400 text-xs mt-1">{{ __('messages.including_taxes_shipping') }}</p>
                                            </div>
                                            <button type="submit" 
                                                    id="place-order-btn"
                                                    class="bg-white text-gray-900 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition-all flex items-center shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                                <i class="fas fa-lock mr-2"></i>
                                                {{ __('messages.place_order') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Step Navigation -->
                            <div class="flex justify-between pt-4">
                                <button type="button" 
                                        class="btn-prev-step px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-all flex items-center"
                                        data-prev="2">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    {{ __('messages.back_to_payment') ?? 'Back to Payment' }}
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary Sidebar -->
                    <div class="xl:col-span-4">
                        <div class="sticky top-24 space-y-6">
                            
                            <!-- Cart Items Summary -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
                                    <h2 class="text-lg font-semibold text-gray-900 flex items-center justify-between">
                                        <span>{{ __('messages.order_summary') }}</span>
                                        <span class="text-sm font-normal text-gray-500">({{ $cart->items->count() }} {{ $cart->items->count() === 1 ? __('messages.item') : __('messages.items') }})</span>
                                    </h2>
                                </div>
                                
                                <div class="p-6">
                                    <!-- Cart Items List -->
                                    <div class="space-y-4 max-h-80 overflow-y-auto" id="cart-items-list">
                                        @foreach($cart->items as $item)
                                        <div class="flex items-start space-x-3 pb-4 border-b border-gray-100 last:border-0 last:pb-0 cart-item" data-item-id="{{ $item->id }}">
                                            <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                                @if($item->artwork->primary_image)
                                                    <img src="{{ asset($item->artwork->primary_image) }}" 
                                                         alt="{{ $item->artwork_title }}" 
                                                         class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex justify-between items-start">
                                                    <h4 class="text-sm font-medium text-gray-900 truncate pr-2">{{ $item->artwork_title }}</h4>
                                                    <button type="button" 
                                                            onclick="removeCartItem({{ $item->id }})"
                                                            class="text-gray-400 hover:text-red-500 transition-colors p-1"
                                                            title="{{ __('messages.remove') }}">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                                <p class="text-xs text-gray-500">{{ $item->artwork->artist->name ?? __('messages.unknown_artist') }}</p>
                                                @if($item->artwork->is_digital)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                        <i class="fas fa-download mr-1"></i> {{ __('messages.digital') }}
                                                    </span>
                                                @endif
                                                
                                                <!-- Quantity Controls -->
                                                <div class="flex items-center justify-between mt-2">
                                                    <div class="flex items-center border border-gray-300 rounded-lg">
                                                        <button type="button" 
                                                                onclick="updateQuantity({{ $item->id }}, -1)"
                                                                class="px-2 py-1 text-gray-600 hover:bg-gray-100 rounded-l-lg transition-colors disabled:opacity-50"
                                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                            <i class="fas fa-minus text-xs"></i>
                                                        </button>
                                                        <span class="px-2 text-sm font-medium text-gray-900 min-w-[2rem] text-center item-quantity">{{ $item->quantity }}</span>
                                                        <button type="button" 
                                                                onclick="updateQuantity({{ $item->id }}, 1)"
                                                                class="px-2 py-1 text-gray-600 hover:bg-gray-100 rounded-r-lg transition-colors disabled:opacity-50"
                                                                {{ $item->quantity >= 99 || $item->quantity >= $item->artwork->stock ? 'disabled' : '' }}>
                                                            <i class="fas fa-plus text-xs"></i>
                                                        </button>
                                                    </div>
                                                    <span class="text-sm font-semibold text-gray-900 item-subtotal">{{ $item->formatted_subtotal }}</span>
                                                </div>
                                                
                                                <!-- Stock Warning -->
                                                @if($item->artwork->stock <= 5 && !$item->artwork->is_digital)
                                                    <p class="text-xs text-amber-600 mt-1">
                                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                                        {{ __('messages.only_x_left', ['count' => $item->artwork->stock]) ?? 'Only ' . $item->artwork->stock . ' left' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Price Breakdown -->
                                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">{{ __('messages.subtotal') }}</span>
                                            <span class="font-medium text-gray-900">{{ $cart->formatted_subtotal ?? $cart->formatted_total }}</span>
                                        </div>
                                        
                                        @if($hasPromoCode && $cart->discount_amount > 0)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-green-600 flex items-center">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ __('messages.discount') }} ({{ $cart->promo_code }})
                                            </span>
                                            <span class="font-medium text-green-600">-{{ $cart->formatted_discount }}</span>
                                        </div>
                                        @endif
                                        
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">{{ __('messages.platform_fee') }}</span>
                                            <span class="font-medium text-gray-900" id="summary-platform-fee">{{ $currencySymbol }}{{ number_format($subtotalAfterDiscount * $feeDecimal, 2) }}</span>
                                        </div>
                                        
                                        @if($hasPhysicalItems)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">{{ __('messages.shipping') }}</span>
                                            <span class="font-medium text-gray-900" id="summary-shipping">
                                                @if($isFreeShippingEligible)
                                                    <span class="text-green-600">{{ __('messages.free') }}</span>
                                                @else
                                                    {{ $currencySymbol }}{{ number_format($shippingCost, 2) }}
                                                @endif
                                            </span>
                                        </div>
                                        @endif
                                        
                                        <div class="pt-4 border-t border-gray-200">
                                            <div class="flex justify-between">
                                                <span class="text-base font-semibold text-gray-900">{{ __('messages.total') }}</span>
                                                <span class="text-xl font-bold text-gray-900" id="summary-total">
                                                    {{ $currencySymbol }}{{ number_format($subtotalAfterDiscount * $feeMultiplier + ($isFreeShippingEligible ? 0 : $shippingCost), 2) }} {{ $currentCurrency !== 'USD' ? $currentCurrency : '' }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1 text-right">
                                                {{ __('messages.including_vat') ?? 'Including all taxes and fees' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Security Badges -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                                <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('messages.secure_checkout') }}</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                                        <span>{{ __('messages.ssl_encrypted') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-lock text-green-600 mr-2"></i>
                                        <span>{{ __('messages.secure_payment') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-undo text-green-600 mr-2"></i>
                                        <span>{{ __('messages.easy_returns') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-headset text-green-600 mr-2"></i>
                                        <span>{{ __('messages.24_7_support') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Need Help -->
                            <div class="bg-blue-50 rounded-xl border border-blue-100 p-6">
                                <h3 class="text-sm font-semibold text-blue-900 mb-2">
                                    <i class="fas fa-question-circle mr-2"></i>
                                    {{ __('messages.need_help') }}
                                </h3>
                                <p class="text-sm text-blue-700 mb-3">
                                    {{ __('messages.help_text') }}
                                </p>
                                <a href="{{ route('contact') }}" class="inline-flex items-center text-sm font-semibold text-blue-800 hover:text-blue-900">
                                    {{ __('messages.contact_us') }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <!-- Empty Cart State -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                    <i class="fas fa-shopping-cart text-gray-400 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('messages.cart_empty') }}</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">
                    {{ __('messages.cart_empty_message') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.artworks.index') }}"
                       class="bg-black text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-all inline-flex items-center justify-center shadow-lg hover:shadow-xl">
                        <i class="fas fa-palette mr-2"></i>
                        {{ __('messages.browse_artworks') }}
                    </a>
                    <a href="{{ route('home') }}"
                       class="border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-all inline-flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        {{ __('messages.go_home') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</section>
</div>
@endsection

@push('scripts')
<script>
    // Currency symbol helper
    function getCurrencySymbol() {
        const currency = '{{ $cart->currency }}';
        return currency === 'USD' ? '$' : '';
    }

    function getCurrencySuffix() {
        const currency = '{{ $cart->currency }}';
        return currency !== 'USD' ? ' ' + currency : '';
    }

    // Step navigation
    function goToStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.checkout-step').forEach(step => {
            step.classList.add('hidden');
        });
        
        // Show target step
        const targetStep = document.getElementById('step-' + stepNumber);
        if (targetStep) {
            targetStep.classList.remove('hidden');
            targetStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // Update progress indicators
        updateProgress(stepNumber);
        
        // Update hidden input
        const stepInput = document.getElementById('current-step-input');
        if (stepInput) {
            stepInput.value = stepNumber;
        }
    }

    // Update progress indicators
    function updateProgress(currentStep) {
        const steps = ['shipping', 'payment', 'review'];
        
        steps.forEach((stepKey, index) => {
            const stepNum = index + 1;
            const circle = document.getElementById('step-circle-' + stepNum);
            const label = document.getElementById('step-label-' + stepNum);
            
            if (circle && label) {
                if (stepNum < currentStep) {
                    // Completed step
                    circle.className = 'step-circle w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 bg-green-500 text-white';
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                    label.className = 'mt-2 text-xs font-medium text-green-600';
                } else if (stepNum === currentStep) {
                    // Current step
                    circle.className = 'step-circle w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 bg-black text-white ring-4 ring-gray-100';
                    label.className = 'mt-2 text-xs font-medium text-gray-900';
                } else {
                    // Future step
                    circle.className = 'step-circle w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 bg-white text-gray-400 border-2 border-gray-300';
                    label.className = 'mt-2 text-xs font-medium text-gray-500';
                }
            }
        });
        
        // Update progress line
        const progressLine = document.getElementById('progress-line');
        if (progressLine) {
            const progress = ((currentStep - 1) / (steps.length - 1)) * 100;
            progressLine.style.width = progress + '%';
        }

        // Update Review Step Summary
        if (currentStep == 3) {
            updateReviewStep();
        }
    }

    // Update the review step with data from previous steps
    function updateReviewStep() {
        // Contact
        const email = document.getElementById('email')?.value || '{{ auth()->user()->email ?? "" }}';
        const phone = document.getElementById('phone')?.value || '';
        const firstName = document.getElementById('first_name')?.value || '{{ auth()->user()->first_name ?? "" }}';
        const lastName = document.getElementById('last_name')?.value || '{{ auth()->user()->last_name ?? "" }}';
        
        document.getElementById('review-email').textContent = email;
        document.getElementById('review-phone').textContent = phone;
        if (document.getElementById('review-name')) {
            document.getElementById('review-name').textContent = firstName + ' ' + lastName;
        }

        // Shipping
        const address = document.getElementById('address')?.value || '';
        const city = document.getElementById('city')?.value || '';
        const state = document.getElementById('state')?.value || '';
        const postalCode = document.getElementById('postal_code')?.value || '';
        const countrySelect = document.getElementById('country');
        const country = countrySelect ? countrySelect.options[countrySelect.selectedIndex]?.text : '';

        document.getElementById('review-address-line').textContent = address;
        document.getElementById('review-city-line').textContent = city + (state ? ', ' + state : '') + ' ' + postalCode;
        document.getElementById('review-country-line').textContent = country;

        // Shipping Method
        const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
        if (selectedShipping) {
            const methodCard = selectedShipping.closest('.shipping-option');
            const methodName = methodCard.querySelector('h3').textContent;
            const methodCost = methodCard.querySelector('.font-bold').textContent;
            
            if (document.getElementById('review-shipping-name')) {
                document.getElementById('review-shipping-name').textContent = methodName;
            }
            if (document.getElementById('review-shipping-cost')) {
                document.getElementById('review-shipping-cost').textContent = methodCost;
            }
        }

        // Payment Method
        const selectedPayment = document.querySelector('input[name="payment_method_id"]:checked');
        if (selectedPayment) {
            const paymentCard = selectedPayment.closest('.payment-option');
            const paymentName = paymentCard.querySelector('h3').textContent;
            
            document.getElementById('review-payment-name').textContent = paymentName;
            
            const paymentNote = document.getElementById('review-payment-note');
            if (selectedPayment.dataset.requiresUpload === 'true') {
                paymentNote.textContent = '{{ __("messages.manual_verification_required") }}';
            } else {
                paymentNote.textContent = '{{ __("messages.instant_processing_secure") }}';
            }
        }

        // Order Notes
        const notes = document.getElementById('order_notes')?.value;
        const notesSection = document.getElementById('review-notes-section');
        if (notes && notes.trim()) {
            notesSection.style.display = 'block';
            document.getElementById('review-notes').textContent = notes;
        } else {
            notesSection.style.display = 'none';
        }

        // Final Total
        const summaryTotal = document.getElementById('summary-total').textContent;
        document.getElementById('final-total').textContent = summaryTotal;
    }

    // Update shipping cost
    function updateShippingCost(method, baseRate) {
        const shippingElement = document.getElementById('summary-shipping');
        const totalElement = document.getElementById('summary-total');
        const currencySymbol = getCurrencySymbol();

        // Check for free shipping
        const subtotalAfterDiscount = {{ $subtotalAfterDiscount }};
        const freeShippingThreshold = {{ $freeShippingThreshold }};
        const isFreeShippingEligible = subtotalAfterDiscount >= freeShippingThreshold && method === 'standard';
        const actualCost = isFreeShippingEligible ? 0 : baseRate;

        // Update shipping display
        if (shippingElement) {
            if (isFreeShippingEligible) {
                shippingElement.innerHTML = '<span class="text-green-600">{{ __('messages.free') }}</span>';
            } else {
                shippingElement.textContent = currencySymbol + actualCost.toFixed(2);
            }
        }

        // Update total
        if (totalElement) {
            const feeMultiplier = {{ $feeMultiplier }};
            const total = subtotalAfterDiscount * feeMultiplier + actualCost;
            totalElement.textContent = currencySymbol + total.toFixed(2) + (currencySymbol !== '$' ? ' {{ $currentCurrency }}' : '');
        }

        // Update visual selection state
        document.querySelectorAll('.shipping-option').forEach(option => {
            const radio = option.querySelector('.shipping-radio');
            const checkIcon = document.getElementById('shipping-check-' + option.dataset.method);

            if (radio.checked) {
                option.classList.add('border-black', 'bg-gray-50');
                option.classList.remove('border-gray-200');
                if (checkIcon) {
                    checkIcon.classList.add('border-black', 'bg-black');
                    checkIcon.classList.remove('border-gray-300');
                    checkIcon.innerHTML = '<i class="fas fa-check text-white text-xs"></i>';
                }
            } else {
                option.classList.remove('border-black', 'bg-gray-50');
                option.classList.add('border-gray-200');
                if (checkIcon) {
                    checkIcon.classList.remove('border-black', 'bg-black');
                    checkIcon.classList.add('border-gray-300');
                    checkIcon.innerHTML = '';
                }
            }
        });
    }

    // Update shipping cost based on method
    document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const costs = {
                'standard': {{ $shippingMethods['standard']['base_rate'] ?? 50 }},
                'express': {{ $shippingMethods['express']['base_rate'] ?? 100 }},
                'premium': {{ $shippingMethods['premium']['base_rate'] ?? 200 }}
            };
            
            updateShippingCost(this.value, costs[this.value]);
        });
    });

    // Handle form submission
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitButton = this.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> {{ __('messages.processing') }}';

        // Clear previous errors
        document.querySelectorAll('.text-red-600').forEach(el => {
            if (el.tagName === 'P') el.remove();
        });
        document.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });

        const formData = new FormData(this);

        // Handle checkbox
        formData.set('agree_terms', this.querySelector('input[name="agree_terms"]').checked ? 'on' : '');

        // Get selected payment method info
        const selectedPayment = document.querySelector('input[name="payment_method_id"]:checked');
        formData.set('requires_upload', selectedPayment ? (selectedPayment.dataset.requiresUpload === 'true' ? '1' : '0') : '0');
        formData.set('payment_method_code', selectedPayment ? selectedPayment.dataset.methodCode : '');

        // Check if screenshot was uploaded for manual payments
        const hasScreenshot = document.getElementById('payment_screenshot') && document.getElementById('payment_screenshot').files.length > 0;
        const requiresManualVerification = selectedPayment ? selectedPayment.dataset.requiresUpload === 'true' : false;

        // For manual payments without screenshot, still allow submission but show warning
        if (requiresManualVerification && !hasScreenshot) {
            console.log('Manual payment selected without screenshot - user will upload later');
        }

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(err => {
                        throw { type: 'validation', data: err };
                    });
                } else {
                    // Server returned HTML error page (CSRF error, 500 error, etc.)
                    if (response.status === 419) {
                        throw { type: 'server', message: '{{ __('messages.csrf_token_expired') }}' };
                    } else if (response.status === 401 || response.status === 403) {
                        throw { type: 'server', message: '{{ __('messages.unauthorized') }}' };
                    } else {
                        throw { type: 'server', message: '{{ __('messages.server_error') }}' };
                    }
                }
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                if (result.requires_upload) {
                    // Redirect to manual payment page for upload
                    window.location.href = result.payment_url;
                } else {
                    window.location.href = result.redirect;
                }
            } else {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                showToast(result.message || '{{ __('messages.error_occurred') }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
            
            if (error.type === 'validation' && error.data.errors) {
                // Display validation errors inline
                Object.entries(error.data.errors).forEach(([field, messages]) => {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('border-red-500');
                        const errorP = document.createElement('p');
                        errorP.className = 'mt-1 text-sm text-red-600';
                        errorP.textContent = messages[0];
                        input.parentNode.appendChild(errorP);
                    }
                });
                showToast('{{ __('messages.please_fix_errors') }}', 'error');
            } else {
                showToast(error.message || '{{ __('messages.error_occurred') }}', 'error');
            }
        });
    });

    // Enhanced Payment Method Selection
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    const paymentInstructionsContainer = document.getElementById('payment-instructions-container');
    const paymentInstructionsTitle = document.getElementById('payment-instructions-title');
    const paymentInstructionsText = document.getElementById('payment-instructions-text');
    const paymentScreenshotContainer = document.getElementById('payment-screenshot-container');

    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('.payment-method-radio');
            const requiresUpload = this.dataset.requiresUpload === 'true';
            const methodCode = this.dataset.methodCode;
            const methodType = this.dataset.type;
            const instructions = this.dataset.instructions;

            // Update all payment option visual states
            paymentMethodCards.forEach(c => {
                const methodId = c.dataset.methodId;
                const checkDiv = document.getElementById('payment-check-' + methodId);
                if (checkDiv) {
                    checkDiv.classList.remove('border-black', 'bg-black');
                    checkDiv.classList.add('border-gray-300');
                    const checkIcon = checkDiv.querySelector('i');
                    if (checkIcon) checkIcon.remove();
                }
                c.classList.remove('border-black', 'bg-gray-50');
                c.classList.add('border-gray-200');
            });

            // Check this card
            this.classList.remove('border-gray-200');
            this.classList.add('border-black', 'bg-gray-50');
            radio.checked = true;

            // Update check indicator
            const methodId = this.dataset.methodId;
            const checkDiv = document.getElementById('payment-check-' + methodId);
            if (checkDiv) {
                checkDiv.classList.remove('border-gray-300');
                checkDiv.classList.add('border-black', 'bg-black');
                if (!checkDiv.querySelector('i')) {
                    checkDiv.innerHTML = '<i class="fas fa-check text-white text-xs"></i>';
                }
            }

            // Show/hide payment instructions
            if (requiresUpload && instructions) {
                paymentInstructionsContainer.classList.remove('hidden');
                paymentInstructionsTitle.textContent = radio.dataset.methodName + ' {{ __('messages.instructions') }}';
                paymentInstructionsText.textContent = instructions;
            } else {
                paymentInstructionsContainer.classList.add('hidden');
            }

            // Show/hide payment screenshot upload for Myanmar payment methods
            if (requiresUpload) {
                paymentScreenshotContainer.classList.remove('hidden');
                paymentScreenshotContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                paymentScreenshotContainer.classList.add('hidden');
                // Clear any selected screenshot when switching to non-manual payment
                removeScreenshot();
            }
        });
    });

    // Auto-select first manual payment if no automatic methods available
    const firstManual = document.querySelector('.payment-method-card[data-requires-upload="true"]');
    if (firstManual && !document.querySelector('.payment-method-card[data-requires-upload="false"]')) {
        firstManual.click();
    }

    // Toggle gift message textarea
    function toggleGiftMessage(checkbox) {
        const container = document.getElementById('gift-message-container');
        if (checkbox.checked) {
            container.classList.remove('hidden');
            document.getElementById('gift_message').focus();
        } else {
            container.classList.add('hidden');
        }
    }

    // Update character count
    function updateCharCount(textarea, counterId) {
        const count = textarea.value.length;
        const max = textarea.getAttribute('maxlength');
        const counter = document.getElementById(counterId);
        counter.textContent = count + '/' + max;
        
        if (count >= max * 0.9) {
            counter.classList.add('text-red-500');
            counter.classList.remove('text-gray-500');
        } else {
            counter.classList.remove('text-red-500');
            counter.classList.add('text-gray-500');
        }
    }

    // Initialize character count
    const giftMessage = document.getElementById('gift_message');
    if (giftMessage && giftMessage.value) {
        updateCharCount(giftMessage, 'gift-char-count');
    }

    // Update cart item quantity
    function updateQuantity(itemId, change) {
        const itemEl = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
        if (!itemEl) return;

        const quantityEl = itemEl.querySelector('.item-quantity');
        const currentQty = parseInt(quantityEl.textContent);
        const newQty = currentQty + change;

        if (newQty < 1 || newQty > 99) return;

        // Show loading state
        const buttons = itemEl.querySelectorAll('button');
        buttons.forEach(btn => btn.disabled = true);

        fetch(`/checkout/cart/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: newQty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity display
                quantityEl.textContent = newQty;
                
                // Update subtotal
                itemEl.querySelector('.item-subtotal').textContent = data.item_subtotal;
                
                // Update cart summary
                updateCartSummary(data.cart, data.shipping_cost);
                
                // Update button states
                buttons[0].disabled = newQty <= 1;
                buttons[1].disabled = false;
                
                showToast('{{ __('messages.quantity_updated') }}', 'success');
            } else {
                showToast(data.message || '{{ __('messages.error_occurred') }}', 'error');
                buttons.forEach(btn => btn.disabled = false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('{{ __('messages.error_occurred') }}', 'error');
            buttons.forEach(btn => btn.disabled = false);
        });
    }

    // Remove cart item
    function removeCartItem(itemId) {
        if (!confirm('{{ __('messages.confirm_remove_item') }}')) {
            return;
        }

        const itemEl = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
        if (!itemEl) return;

        itemEl.style.opacity = '0.5';

        fetch(`{{ route('checkout.cart.remove', ':itemId') }}`.replace(':itemId', itemId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                if (data.cart_empty) {
                    // Reload page to show empty cart state
                    window.location.reload();
                } else {
                    // Remove item from DOM
                    itemEl.remove();
                    
                    // Update cart summary
                    updateCartSummary(data.cart, 0);
                    
                    showToast('{{ __('messages.item_removed') }}', 'success');
                }
            } else {
                itemEl.style.opacity = '1';
                showToast(data.message || '{{ __('messages.error_occurred') }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error details:', error);
            itemEl.style.opacity = '1';
            showToast(error.message || '{{ __('messages.error_occurred') }}', 'error');
        });
    }

    // Update cart summary display
    function updateCartSummary(cart, shippingCost) {
        // Update item count
        const itemCountEl = document.querySelector('#order-summary h2 span:last-child');
        if (itemCountEl) {
            itemCountEl.textContent = `(${cart.item_count} ${cart.item_count === 1 ? '{{ __('messages.item') }}' : '{{ __('messages.items') }}'})`;
        }
        
        // Update totals (you may need to adjust selectors based on your actual HTML)
        const subtotalEl = document.querySelector('.summary-subtotal');
        if (subtotalEl) subtotalEl.textContent = cart.subtotal;
        
        const discountEl = document.querySelector('.summary-discount');
        if (discountEl && cart.discount !== '{{ $currencySymbol }}0.00') {
            discountEl.textContent = '-' + cart.discount;
        }
        
        // Recalculate and update total
        const currencySymbol = getCurrencySymbol();
        const currencySuffix = getCurrencySuffix();
        const subtotal = parseFloat(cart.subtotal.replace(/[^\d.]/g, '')) || 0;
        const feeMultiplier = {{ $feeMultiplier }};
        const total = subtotal * feeMultiplier + shippingCost;
        
        const totalEl = document.getElementById('summary-total');
        if (totalEl) {
            totalEl.textContent = currencySymbol + total.toFixed(2) + currencySuffix;
        }
    }

    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Allow only numbers, spaces, dashes, parentheses, and plus
            let value = e.target.value.replace(/[^\d\s\-\(\)\+]/g, '');
            e.target.value = value;
        });
        
        phoneInput.addEventListener('blur', function(e) {
            let value = e.target.value.replace(/\s+/g, ' ').trim();
            e.target.value = value;
        });
    }

    // Real-time validation feedback
    document.querySelectorAll('input[required], textarea[required]').forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            // Remove error styling when user starts typing
            this.classList.remove('border-red-500');
            const errorEl = this.parentNode.querySelector('.text-red-600');
            if (errorEl && errorEl.id.startsWith('error-')) {
                errorEl.classList.add('hidden');
            }
        });
    });

    // Field validation
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        let errorMsg = '';

        if (!value && field.hasAttribute('required')) {
            isValid = false;
            errorMsg = '{{ __('messages.field_required') }}';
        } else if (value) {
            switch (fieldName) {
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        errorMsg = '{{ __('messages.invalid_email') }}';
                    }
                    break;
                case 'phone':
                    const phoneRegex = /^[\+\d\s\-\(\)]+$/;
                    if (!phoneRegex.test(value) || value.length < 8) {
                        isValid = false;
                        errorMsg = '{{ __('messages.invalid_phone') }}';
                    }
                    break;
                case 'postal_code':
                    if (value.length < 2 || value.length > 20) {
                        isValid = false;
                        errorMsg = '{{ __('messages.invalid_postal_code') }}';
                    }
                    break;
            }
        }

        // Show/hide error
        const errorEl = document.getElementById('error-' + fieldName);
        if (errorEl) {
            if (!isValid) {
                errorEl.textContent = errorMsg;
                errorEl.classList.remove('hidden');
                field.classList.add('border-red-500');
            } else {
                errorEl.classList.add('hidden');
                field.classList.remove('border-red-500');
            }
        }

        return isValid;
    }

    // Validate all fields before step navigation
    function validateStep(stepNumber) {
        const step = document.getElementById('step-' + stepNumber);
        const requiredFields = step.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    // Enhanced step navigation with validation
    document.querySelectorAll('.btn-next-step').forEach(btn => {
        btn.addEventListener('click', function() {
            const currentStep = this.closest('.checkout-step').id.replace('step-', '');
            if (validateStep(currentStep)) {
                goToStep(this.dataset.next);
            } else {
                showToast('{{ __('messages.please_fill_required') }}', 'error');
            }
        });
    });

    // Toast notification helper
    function showToast(message, type = 'info') {
        // Check if toast container exists, create if not
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(toastContainer);
        }

        const toast = document.createElement('div');
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 transform transition-all duration-300 translate-y-full opacity-0`;
        toast.innerHTML = `<i class="fas ${icons[type]}"></i> <span>${message}</span>`;

        toastContainer.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-full', 'opacity-0');
        });

        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Payment Screenshot Upload Handler
    function handleScreenshotUpload(input) {
        const file = input.files[0];
        const placeholder = document.getElementById('screenshot-placeholder');
        const preview = document.getElementById('screenshot-preview');
        const previewImg = document.getElementById('screenshot-preview-img');
        const filenameSpan = document.getElementById('screenshot-filename');
        const uploadArea = document.getElementById('screenshot-upload-area');

        if (!file) return;

        // Validate file size (max 5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            showToast('{{ __('messages.file_too_large') }}', 'error');
            input.value = '';
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showToast('{{ __('messages.invalid_file_type') }}', 'error');
            input.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            filenameSpan.textContent = file.name;
            placeholder.classList.add('hidden');
            preview.classList.remove('hidden');
            uploadArea.classList.add('bg-green-50', 'border-green-300');
            uploadArea.classList.remove('hover:bg-yellow-100');

            showToast('{{ __('messages.screenshot_uploaded') }}', 'success');
        };
        reader.readAsDataURL(file);
    }

    // Remove Screenshot Handler
    function removeScreenshot(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }

        const input = document.getElementById('payment_screenshot');
        const placeholder = document.getElementById('screenshot-placeholder');
        const preview = document.getElementById('screenshot-preview');
        const previewImg = document.getElementById('screenshot-preview-img');
        const uploadArea = document.getElementById('screenshot-upload-area');

        // Reset file input
        input.value = '';

        // Reset preview
        previewImg.src = '';
        placeholder.classList.remove('hidden');
        preview.classList.add('hidden');

        // Reset styling
        uploadArea.classList.remove('bg-green-50', 'border-green-300');
        uploadArea.classList.add('hover:bg-yellow-100');

        showToast('{{ __('messages.screenshot_removed') }}', 'info');
    }

    // Drag and drop support for screenshot upload
    const screenshotUploadArea = document.getElementById('screenshot-upload-area');
    if (screenshotUploadArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            screenshotUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            screenshotUploadArea.addEventListener(eventName, () => {
                screenshotUploadArea.classList.add('bg-yellow-100', 'border-yellow-500');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            screenshotUploadArea.addEventListener(eventName, () => {
                screenshotUploadArea.classList.remove('bg-yellow-100', 'border-yellow-500');
            }, false);
        });

        screenshotUploadArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                const input = document.getElementById('payment_screenshot');
                input.files = files;
                handleScreenshotUpload(input);
            }
        }, false);
    }

    // Promo Code functionality
    const applyPromoBtn = document.getElementById('apply-promo-btn');
    const removePromoBtn = document.getElementById('remove-promo-btn');
    const promoCodeInput = document.getElementById('promo-code-input');
    const promoError = document.getElementById('promo-error');
    const promoSuccess = document.getElementById('promo-success');

    if (applyPromoBtn) {
        applyPromoBtn.addEventListener('click', function() {
            const code = promoCodeInput.value.trim().toUpperCase();
            if (!code) {
                promoError.textContent = '{{ __("messages.enter_promo_code") ?? "Please enter a promo code" }}';
                promoError.classList.remove('hidden');
                return;
            }

            applyPromoBtn.disabled = true;
            applyPromoBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('{{ route('cart.promo.apply') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    promoSuccess.textContent = data.message || '{{ __("messages.promo_applied") }}';
                    promoSuccess.classList.remove('hidden');
                    promoError.classList.add('hidden');
                    showToast(data.message || '{{ __("messages.promo_applied") }}', 'success');
                    
                    // Reload page to show updated totals
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    promoError.textContent = data.message || '{{ __("messages.invalid_promo") }}';
                    promoError.classList.remove('hidden');
                    promoSuccess.classList.add('hidden');
                    showToast(data.message || '{{ __("messages.invalid_promo") }}', 'error');
                }
            })
            .catch(error => {
                console.error('Error applying promo code:', error);
                promoError.textContent = '{{ __("messages.error_occurred") }}';
                promoError.classList.remove('hidden');
                showToast('{{ __("messages.error_occurred") }}', 'error');
            })
            .finally(() => {
                applyPromoBtn.disabled = false;
                applyPromoBtn.innerHTML = '{{ __("messages.apply") }}';
            });
        });
    }

    if (removePromoBtn) {
        removePromoBtn.addEventListener('click', function() {
            removePromoBtn.disabled = true;
            removePromoBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('{{ route('cart.promo.remove') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || '{{ __("messages.promo_removed") }}', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(data.message || '{{ __("messages.error_occurred") }}', 'error');
                }
            })
            .catch(error => {
                console.error('Error removing promo code:', error);
                showToast('{{ __("messages.error_occurred") }}', 'error');
            })
            .finally(() => {
                removePromoBtn.disabled = false;
                removePromoBtn.innerHTML = '<i class="fas fa-times mr-1"></i>{{ __("messages.remove") }}';
            });
        });
    }
</script>
@endpush
