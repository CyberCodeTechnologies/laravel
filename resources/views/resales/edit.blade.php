@extends('layouts.app')

@section('title', 'Edit Resale Listing - Panchi Gallery')
@section('meta-description', 'Edit your artwork resale listing.')

@section('content')
<!-- Header -->
<section class="bg-gray-900 text-white py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-serif text-3xl font-bold mb-2">Edit Resale Listing</h1>
        <p class="text-gray-300">Update your listing details</p>
    </div>
</section>

<!-- Form -->
<section class="py-12 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <!-- Artwork Preview -->
            <div class="flex gap-4 mb-8 p-4 bg-gray-50 rounded-lg">
                <img src="{{ $resale->artwork->primary_image ?? asset('images/placeholder-artwork.jpg') }" 
                     alt="{{ $resale->artwork->title }}" 
                     class="w-24 h-24 object-cover rounded">
                <div>
                    <h3 class="font-serif font-bold">{{ $resale->artwork->title }}</h3>
                    <p class="text-gray-600 text-sm">by {{ $resale->artwork->artist->name ?? 'Unknown' }}</p>
                    <p class="text-gray-500 text-sm mt-1">Current Status: <span class="capitalize font-medium">{{ $resale->status }}</span></p>
                </div>
            </div>

            <form action="{{ route('resales.update', $resale) }}" method="POST">
                @csrf
                @method('PUT')

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
                                   value="{{ old('asking_price', $resale->asking_price) }}"
                                   class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
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
                            <option value="excellent" {{ $resale->condition === 'excellent' ? 'selected' : '' }}>Excellent - Like new</option>
                            <option value="very_good" {{ $resale->condition === 'very_good' ? 'selected' : '' }}>Very Good - Minor wear</option>
                            <option value="good" {{ $resale->condition === 'good' ? 'selected' : '' }}>Good - Some wear visible</option>
                            <option value="fair" {{ $resale->condition === 'fair' ? 'selected' : '' }}>Fair - Significant wear</option>
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
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('condition_description', $resale->condition_description) }}</textarea>
                    </div>

                    <!-- Reason for Selling -->
                    <div>
                        <label for="reason_for_selling" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Selling
                        </label>
                        <textarea id="reason_for_selling" 
                                  name="reason_for_selling" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('reason_for_selling', $resale->reason_for_selling) }}</textarea>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <a href="{{ route('collector.resales') }}" 
                       class="flex-1 inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                        Update Listing
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
