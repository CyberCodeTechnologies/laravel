@extends('admin.layouts.app')

@section('title', 'Manage Payment Methods - Admin')
@section('meta-description', 'Manage payment methods and gateways on Panchi Gallery platform')

@section('header', 'Manage Payment Methods')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Methods -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Total Methods</p>
                        <p class="text-2xl font-bold text-blue-900">{{ App\Models\PaymentMethod::count() }}</p>
                        <p class="text-xs text-blue-700 mt-1">Payment gateways configured</p>
                    </div>
                </div>
            </div>

            <!-- Active Methods -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Active</p>
                        <p class="text-2xl font-bold text-green-900">{{ App\Models\PaymentMethod::where('is_active', true)->count() }}</p>
                        <p class="text-xs text-green-700 mt-1">Currently available</p>
                    </div>
                </div>
            </div>

            <!-- Manual Verification -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-hand-paper text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Manual Verification</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ App\Models\PaymentMethod::where('requires_manual_verification', true)->count() }}</p>
                        <p class="text-xs text-yellow-700 mt-1">Require admin approval</p>
                    </div>
                </div>
            </div>

            <!-- Stripe Methods -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fab fa-stripe-s text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Stripe</p>
                        <p class="text-2xl font-bold text-purple-900">{{ App\Models\PaymentMethod::where('type', 'stripe')->count() }}</p>
                        <p class="text-xs text-purple-700 mt-1">Stripe integrations</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Actions -->
<section class="py-6 bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="flex gap-3">
                <a href="{{ route('admin.payment-methods.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Add Payment Method
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Payment Methods Table -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verification</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($paymentMethods as $method)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($method->logo)
                                            <img src="{{ asset('storage/' . $method->logo) }}" class="h-10 w-10 rounded object-cover mr-3" alt="{{ $method->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center mr-3">
                                                <i class="fas fa-credit-card text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $method->name }}</div>
                                            @if($method->instructions)
                                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($method->instructions, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-mono">{{ $method->code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $method->type === 'stripe' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $method->type === 'paypal' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $method->type === 'bank_transfer' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $method->type === 'mobile_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $method->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($method->is_active)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($method->requires_manual_verification)
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Manual
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Auto
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $method->sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$method->is_active)
                                            <form method="POST" action="{{ route('admin.payment-methods.activate', $method->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Activate" onclick="return confirm('Activate this payment method?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.payment-methods.deactivate', $method->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Deactivate" onclick="return confirm('Deactivate this payment method?')">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.payment-methods.delete', $method->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete" onclick="return confirm('Delete this payment method? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-credit-card text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No payment methods configured</p>
                                        <p class="text-sm">Add payment methods to enable purchases on the platform.</p>
                                        <a href="{{ route('admin.payment-methods.create') }}" class="text-blue-600 hover:text-blue-900 font-medium mt-2 inline-block">
                                            Add Payment Method
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
