@extends('layouts.app')

@section('title', 'Verify Certificate - Panchi Gallery')
@section('meta-description', 'Verify the authenticity of artwork certificates.')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2 text-center">Verify Certificate</h1>
        <p class="text-gray-600 text-center mb-8">Enter the certificate code to verify artwork authenticity</p>

        <form action="{{ route('verify.certificate', ['certificate_code' => '']) }}" method="GET" class="space-y-6">
            <div>
                <label for="certificate_code" class="block text-sm font-medium text-gray-700 mb-2">
                    Certificate Code
                </label>
                <input type="text" 
                       id="certificate_code" 
                       name="certificate_code" 
                       required
                       placeholder="e.g., CERT-ABC12345"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <p class="mt-2 text-sm text-gray-500">
                    The certificate code can be found on the physical certificate or in your ownership records.
                </p>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition font-medium">
                Verify Certificate
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-3">How to find your certificate code:</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Check your ownership certificate PDF document</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>View your artwork ownership history in your collector dashboard</span>
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Scan the QR code on your certificate (if available)</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
