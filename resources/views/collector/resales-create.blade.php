@extends('layouts.app')

@section('title', 'Create Resale Listing - Panchi Gallery')
@section('meta-description', 'List your artwork for resale on the secondary market.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Create Resale Listing</h1>
        <p class="text-gray-300">List "{{ $artwork->title }}" for resale</p>
    </div>
</section>

<!-- Form -->
<section class="py-12 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <!-- Artwork Preview -->
            <div class="flex gap-4 mb-8 p-4 bg-gray-50 rounded-lg">
                <img src="{{ $artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }}" 
                     alt="{{ $artwork->title }}" 
                     class="w-24 h-24 object-cover rounded">
                <div>
                    <h3 class="font-serif font-bold">{{ $artwork->title }}</h3>
                    <p class="text-gray-600 text-sm">by {{ $artwork->artist->name ?? 'Unknown' }}</p>
                    <p class="text-gray-500 text-sm mt-1">Original Price: ${{ number_format($artwork->original_price ?? $artwork->price, 2) }}</p>
                </div>
            </div>

            <form action="{{ route('collector.resales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="artwork_id" value="{{ $artwork->id }}">

                <div class="space-y-6">
                    <!-- Asking Price -->
                    <div>
                        <label for="asking_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Asking Price (USD) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" 
                                   id="asking_price" 
                                   name="asking_price" 
                                   step="0.01" 
                                   min="1"
                                   required
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter your asking price">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Platform fee: {{ \App\Models\Transaction::getPlatformFeePercentage() }}% of sale price</p>
                        @error('asking_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Condition -->
                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">
                            Artwork Condition <span class="text-red-500">*</span>
                        </label>
                        <select id="condition" 
                                name="condition" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select condition</option>
                            <option value="excellent">Excellent - Like new</option>
                            <option value="very_good">Very Good - Minor wear</option>
                            <option value="good">Good - Some wear visible</option>
                            <option value="fair">Fair - Significant wear</option>
                        </select>
                        @error('condition')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Condition Description -->
                    <div>
                        <label for="condition_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Condition Description
                        </label>
                        <textarea id="condition_description" 
                                  name="condition_description" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Describe the current condition of the artwork..."></textarea>
                        @error('condition_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reason for Selling -->
                    <div>
                        <label for="reason_for_selling" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Selling
                        </label>
                        <textarea id="reason_for_selling" 
                                  name="reason_for_selling" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Why are you selling this artwork? (Optional)"></textarea>
                    </div>

                    <!-- Provenance Notes -->
                    <div>
                        <label for="provenance_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Provenance Notes
                        </label>
                        <textarea id="provenance_notes" 
                                  name="provenance_notes" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Any additional provenance information..."></textarea>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-3">
                        <input type="checkbox" 
                               id="terms" 
                               name="terms" 
                               required
                               class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="terms" class="text-sm text-gray-600">
                            I confirm that I am the rightful owner of this artwork and have the Certificate of Authenticity. 
                            I agree to the <a href="{{ route('terms') }}" class="text-blue-600 hover:underline" target="_blank">Terms of Service</a> 
                            and <a href="{{ route('privacy') }}" class="text-blue-600 hover:underline" target="_blank">Privacy Policy</a>.
                        </label>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <a href="{{ route('collector.artworks') }}" 
                       class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                        Submit Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
