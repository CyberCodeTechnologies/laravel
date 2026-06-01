@extends('layouts.app')

@section('title', 'Dashboard - Panchi Gallery')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900">Welcome, {{ auth()->user()->name }}</h1>
            <p class="text-gray-600 mt-2">Your account is being set up</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-user text-3xl text-blue-600"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Account Setup</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">
                Your account is currently being configured. Please complete your profile to access all features.
            </p>
            <a href="{{ route('profile') }}" class="bg-black text-white px-8 py-3 rounded-lg hover:bg-gray-800 transition inline-block">
                Complete Profile
            </a>
        </div>
    </div>
</div>
@endsection
