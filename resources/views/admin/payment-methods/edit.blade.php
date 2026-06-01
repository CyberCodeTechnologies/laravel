@extends('admin.layouts.app')

@section('title', 'Edit Payment Method - Admin')

@section('header', 'Edit Payment Method')

@section('admin_content')
<section class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.payment-methods.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back to Payment Methods
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Edit: {{ $paymentMethod->name }}</h2>
                    <p class="text-sm text-gray-500">Update configuration for this payment method.</p>
                </div>
                <div class="flex items-center">
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $paymentMethod->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $paymentMethod->is_active ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Method Name *</label>
                        <input type="text" name="name" value="{{ old('name', $paymentMethod->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unique Code *</label>
                        <input type="text" name="code" value="{{ old('code', $paymentMethod->code) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 cursor-not-allowed" readonly>
                        <p class="text-xs text-gray-400 mt-1">Code cannot be changed after creation.</p>
                        @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <select name="type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="mobile_payment" {{ old('type', $paymentMethod->type) == 'mobile_payment' ? 'selected' : '' }}>Mobile Payment (KBZ, Wave, etc.)</option>
                            <option value="bank_transfer" {{ old('type', $paymentMethod->type) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="stripe" {{ old('type', $paymentMethod->type) == 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="paypal" {{ old('type', $paymentMethod->type) == 'paypal' ? 'selected' : '' }}>PayPal</option>
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $paymentMethod->sort_order) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('sort_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Checkboxes -->
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="requires_manual_verification" value="1" {{ old('requires_manual_verification', $paymentMethod->requires_manual_verification) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Requires Manual Verification</span>
                    </label>
                </div>

                <!-- Instructions -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instructions (for manual payment)</label>
                    <textarea name="instructions" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
                    @error('instructions') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Logo -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Logo</label>
                        @if($paymentMethod->logo)
                            <div class="mb-2 relative w-24 h-24">
                                <img src="{{ asset('storage/' . $paymentMethod->logo) }}" class="w-full h-full object-contain border rounded p-1">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- QR Code -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">MMQR Code (Optional)</label>
                        @if($paymentMethod->qr_code)
                            <div class="mb-2 relative w-32 h-32">
                                <img src="{{ asset('storage/' . $paymentMethod->qr_code) }}" class="w-full h-full object-contain border rounded p-1">
                            </div>
                        @endif
                        <input type="file" name="qr_code" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        <p class="text-xs text-gray-400 mt-1">Upload a QR code for mobile payments (KBZ, Wave, etc.)</p>
                        @error('qr_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end gap-3">
                    <a href="{{ route('admin.payment-methods.index') }}"
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md transition">
                        Update Payment Method
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
