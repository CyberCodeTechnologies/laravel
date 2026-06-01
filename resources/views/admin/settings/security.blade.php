@extends('admin.layouts.app')

@section('title', 'Security Settings - Admin')
@section('meta-description', 'Configure security settings on Panchi Gallery')

@section('header', 'Security Settings')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Security Status -->
            <div class="bg-red-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-red-600 font-medium">Security Status</p>
                        <p class="text-2xl font-bold text-red-900">{{ config('app.debug') ? 'Dev' : 'Secure' }}</p>
                        <p class="text-xs text-red-700 mt-1">{{ config('app.env') }} mode</p>
                    </div>
                </div>
            </div>

            <!-- Two-Factor Auth -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-mobile-alt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">2FA Status</p>
                        <p class="text-2xl font-bold text-blue-900">Optional</p>
                        <p class="text-xs text-blue-700 mt-1">Two-factor auth</p>
                    </div>
                </div>
            </div>

            <!-- Password Policy -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-lock text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Password Policy</p>
                        <p class="text-2xl font-bold text-green-900">Enabled</p>
                        <p class="text-xs text-green-700 mt-1">Strong passwords</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Security Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('admin.settings.security.update') }}" class="bg-white rounded-lg shadow-md">
                    @csrf
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Security Configuration</h2>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="session_timeout" class="block text-sm font-medium text-gray-700 mb-2">Session Timeout (Minutes)</label>
                                    <input type="number" id="session_timeout" name="session_timeout" value="{{ $settings['session_timeout'] ?? 120 }}" min="5" max="10080"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                                <div>
                                    <label for="max_login_attempts" class="block text-sm font-medium text-gray-700 mb-2">Max Login Attempts</label>
                                    <input type="number" id="max_login_attempts" name="max_login_attempts" value="{{ $settings['max_login_attempts'] ?? 5 }}" min="3" max="10"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                </div>
                            </div>

                            <div>
                                <label for="password_min_length" class="block text-sm font-medium text-gray-700 mb-2">Minimum Password Length</label>
                                <input type="number" id="password_min_length" name="password_min_length" value="{{ $settings['password_min_length'] ?? 8 }}" min="6" max="32"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Requirements</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="password_require_uppercase" value="1" {{ ($settings['password_require_uppercase'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-900">Require uppercase letters</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="password_require_lowercase" value="1" {{ ($settings['password_require_lowercase'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-900">Require lowercase letters</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="password_require_numbers" value="1" {{ ($settings['password_require_numbers'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-900">Require numbers</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="password_require_special" value="1" {{ ($settings['password_require_special'] ?? '1') == '1' ? 'checked' : '' }}
                                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-900">Require special characters</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="force_2fa_admin" value="1" {{ ($settings['force_2fa_admin'] ?? '0') == '1' ? 'checked' : '' }}
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium text-gray-900">Force 2FA for Admins</span>
                                        <p class="text-xs text-gray-500">Require two-factor authentication for admin accounts</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <a href="{{ route('admin.settings') }}" class="mr-4 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-save mr-2"></i>Save Security Settings
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Security Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Environment</span>
                            <span class="text-sm font-medium {{ config('app.env') === 'production' ? 'text-green-600' : 'text-orange-600' }}">{{ config('app.env') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Debug Mode</span>
                            <span class="text-sm font-medium {{ config('app.debug') ? 'text-red-600' : 'text-green-600' }}">{{ config('app.debug') ? 'On' : 'Off' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">HTTPS</span>
                            <span class="text-sm font-medium text-gray-900">{{ request()->secure() ? 'Enabled' : 'Disabled' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
