@extends('layouts.app')

@section('title', __('messages.forgot_password') . ' - Panchi Gallery')
@section('meta-description', __('messages.forgot_password_meta_description'))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="flex justify-center items-center space-x-2 mb-6">
                <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center">
                    <span class="text-white font-serif text-lg font-bold">P</span>
                </div>
                <span class="font-serif text-2xl font-bold text-black">Panchi Gallery</span>
            </a>
            <h2 class="text-3xl font-bold text-gray-900">{{ __('messages.reset_password') }}</h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('messages.enter_email_reset') }}
            </p>
        </div>

        <!-- Forgot Password Form -->
        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <!-- Success Message -->
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <div>
                            <p class="font-medium">{{ __('messages.please_fix_error') }}</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        {{ __('messages.email_address') }}
                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           autocomplete="email"
                           required
                           value="{{ old('email') }}"
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="{{ __('messages.enter_email_address') }}">
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-paper-plane group-hover:text-indigo-400 text-indigo-500"></i>
                    </span>
                    {{ __('messages.send_reset_link') }}
                </button>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('messages.back_to_login') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
