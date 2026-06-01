@extends('admin.layouts.app')

@section('title', 'Email Settings - Admin')
@section('meta-description', 'Configure email settings on Panchi Gallery')

@section('header', 'Email Settings')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Mail Driver -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-paper-plane text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Mail Driver</p>
                        <p class="text-2xl font-bold text-purple-900">{{ strtoupper($settings['mail_driver'] ?? config('mail.default', 'smtp')) }}</p>
                        <p class="text-xs text-purple-700 mt-1">Current driver</p>
                    </div>
                </div>
            </div>

            <!-- From Address -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-at text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">From Address</p>
                        <p class="text-2xl font-bold text-blue-900 truncate">{{ $settings['mail_from_address'] ?? config('mail.from.address') }}</p>
                        <p class="text-xs text-blue-700 mt-1">Sender email</p>
                    </div>
                </div>
            </div>

            <!-- Mail Status -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Mail Status</p>
                        <p class="text-2xl font-bold text-green-900">Active</p>
                        <p class="text-xs text-green-700 mt-1">System ready</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Email Settings Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Settings Area -->
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('admin.settings.email.update') }}" class="bg-white rounded-lg shadow-md">
                    @csrf
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Email Configuration</h2>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="mail_driver" class="block text-sm font-medium text-gray-700 mb-2">Mail Driver</label>
                                <select id="mail_driver" name="mail_driver" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="smtp" {{ ($settings['mail_driver'] ?? config('mail.default')) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="mail" {{ ($settings['mail_driver'] ?? config('mail.default')) === 'mail' ? 'selected' : '' }}>PHP Mail</option>
                                    <option value="sendmail" {{ ($settings['mail_driver'] ?? config('mail.default')) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ ($settings['mail_driver'] ?? config('mail.default')) === 'log' ? 'selected' : '' }}>Log (Testing)</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">Select the email driver to use for sending emails.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                                    <input type="text" id="mail_host" name="mail_host" value="{{ $settings['mail_host'] ?? config('mail.mailers.smtp.host') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                           placeholder="smtp.example.com">
                                </div>
                                <div>
                                    <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                                    <input type="number" id="mail_port" name="mail_port" value="{{ $settings['mail_port'] ?? config('mail.mailers.smtp.port') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                           placeholder="587">
                                </div>
                            </div>

                            <div>
                                <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                                <input type="text" id="mail_username" name="mail_username" value="{{ $settings['mail_username'] ?? config('mail.mailers.smtp.username') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                       placeholder="your-email@example.com">
                            </div>

                            <div>
                                <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                                <input type="password" id="mail_password" name="mail_password" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                       placeholder="Leave blank to keep current password">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                                    <select id="mail_encryption" name="mail_encryption"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                        <option value="tls" {{ ($settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption')) === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ ($settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption')) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ empty($settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption')) ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                                    <input type="email" id="mail_from_address" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? config('mail.from.address') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                </div>
                            </div>

                            <div>
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                                <input type="text" id="mail_from_name" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? config('mail.from.name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 flex justify-end border-t">
                        <a href="{{ route('admin.settings') }}" class="mr-4 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                            <i class="fas fa-save mr-2"></i>Save Email Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Email Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Email Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Environment</span>
                            <span class="text-sm font-medium text-gray-900">{{ config('app.env') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Queue Status</span>
                            <span class="text-sm font-medium text-gray-900">{{ config('queue.default') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Preview Mode</span>
                            <span class="text-sm font-medium {{ ($settings['mail_driver'] ?? config('mail.default')) === 'log' ? 'text-orange-600' : 'text-gray-900' }}">
                                {{ ($settings['mail_driver'] ?? config('mail.default')) === 'log' ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button type="button" onclick="testEmail()" class="flex items-center w-full p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-paper-plane text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Send Test Email</span>
                        </button>
                        <a href="{{ route('admin.settings.cache') }}" class="flex items-center p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                            <i class="fas fa-bolt text-orange-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-900">Clear Mail Cache</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function testEmail() {
    alert('Test email functionality will send a test email to your configured address.');
}
</script>
@endsection
