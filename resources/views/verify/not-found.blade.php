@extends('layouts.app')

@section('title', 'Certificate Not Found - Panchi Gallery')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
        </div>
        
        <h1 class="text-2xl font-serif font-bold text-gray-900 mb-4">Certificate Not Found</h1>
        
        <p class="text-gray-600 mb-6">
            We couldn't find a certificate with the code <strong>{{ request('certificate_code') ?? request('code') ?? 'provided' }}</strong>.
        </p>
        
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-500">
                Please check the code and try again. If you believe this is an error, please contact our support team.
            </p>
        </div>
        
        <div class="space-y-3">
            <a href="{{ route('verify.form') }}" class="block w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition">
                Try Another Code
            </a>
            <a href="{{ route('contact') }}" class="block w-full border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50 transition">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
