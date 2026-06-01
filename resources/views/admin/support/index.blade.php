@extends('admin.layouts.app')

@section('title', 'Support Center - Admin')
@section('meta-description', 'Manage support tickets and customer inquiries on Panchi Gallery')

@section('header', 'Support Center')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Support Overview -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-headset text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Support Center</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['total'] ?? 0 }}</p>
                        <p class="text-xs text-blue-700 mt-1">Total messages</p>
                    </div>
                </div>
            </div>

            <!-- Response Time -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Response Time</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-xs text-green-700 mt-1">Pending replies</p>
                    </div>
                </div>
            </div>

            <!-- Available Resources -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-book text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Documentation</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['faq_count'] ?? 0 }}</p>
                        <p class="text-xs text-purple-700 mt-1">Published FAQs</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-envelope text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Contact Form</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['unread'] ?? 0 }}</p>
                        <p class="text-xs text-yellow-700 mt-1">Unread messages</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Support Management Grid -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Messages -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Contact Messages</h2>
                    <a href="{{ route('admin.support.contacts') }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                        View All →
                    </a>
                </div>
                
                @if(($recentContacts ?? collect())->isNotEmpty())
                    <ul class="divide-y divide-gray-100">
                        @foreach($recentContacts as $msg)
                            <li class="py-3 flex justify-between items-start gap-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $msg->name }}</p>
                                    <p class="text-sm text-gray-500 truncate max-w-xs">{{ $msg->subject }}</p>
                                </div>
                                <a href="{{ admin_route('admin.support.contacts.show', ['contact' => $msg->id]) }}" class="text-indigo-600 text-sm shrink-0">View</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No contact messages yet</p>
                    </div>
                @endif
            </div>

            <!-- FAQ Management -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">FAQ Management</h2>
                    <a href="{{ route('admin.support.faq') }}" class="text-indigo-600 hover:text-indigo-700 text-sm">
                        Manage →
                    </a>
                </div>
                
                <div class="text-center py-8">
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['faq_count'] ?? 0 }}</p>
                    <p class="text-gray-500 mt-2">FAQs in help center</p>
                    <a href="{{ route('admin.support.faq') }}" class="inline-block mt-4 text-indigo-600 text-sm">Manage FAQs →</a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.support.contacts') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-inbox text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900">View Messages</h3>
                        <p class="text-sm text-gray-500">Manage contact form submissions</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.support.faq') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-book text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900">Manage FAQ</h3>
                        <p class="text-sm text-gray-500">Update help center articles</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('contact') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition cursor-pointer" target="_blank">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-external-link-alt text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900">View Contact Page</h3>
                        <p class="text-sm text-gray-500">See public contact form</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>
@endsection
