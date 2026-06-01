@extends('layouts.app')

@section('title', 'Payment Methods - Panchi Gallery')
@section('meta-description', 'Select your preferred payment method.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Payment Methods</h1>
        <p class="text-gray-300">Select how you'd like to pay</p>
    </div>
</section>

<!-- Payment Methods -->
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($paymentMethods as $method)
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        @if($method->icon)
                        <img src="{{ asset('images/payment/' . $method->icon) }}" alt="{{ $method->name }}" class="w-8 h-8">
                        @else
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ $method->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $method->description }}</p>
                        
                        @if($method->requires_manual_verification)
                        <div class="mt-3 p-3 bg-yellow-50 rounded-lg">
                            <p class="text-sm text-yellow-800">
                                <span class="font-medium">Note:</span> Payment requires manual verification
                            </p>
                        </div>
                        @endif
                        
                        <button class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                            Select
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($paymentMethods->count() === 0)
        <div class="text-center py-16 bg-white rounded-lg">
            <p class="text-gray-500">No payment methods available at this time.</p>
        </div>
        @endif
    </div>
</section>
@endsection
