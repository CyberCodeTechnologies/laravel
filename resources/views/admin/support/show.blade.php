@extends('admin.layouts.app')

@section('title', 'Contact Details - Admin')
@section('header', 'Contact Details')

@section('admin_content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold">{{ $contact->subject }}</h2>
                <p class="text-sm text-gray-500 mt-1">Received {{ $contact->created_at->format('M d, Y h:i A') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                {{ $contact->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $contact->status === 'responded' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $contact->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                {{ ucfirst($contact->status) }}
            </span>
        </div>

        <div class="p-6 border-b bg-gray-50 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">From</p>
                <p class="font-medium">{{ $contact->name }}</p>
                <p class="text-sm text-gray-500">{{ $contact->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Subject</p>
                <p class="font-medium">{{ $contact->subject }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Read</p>
                <p class="font-medium">{{ $contact->is_read ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        <div class="p-6 border-b">
            <h3 class="text-sm font-medium text-gray-700 mb-3">Message</h3>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $contact->message }}</p>
        </div>

        @if($contact->admin_notes)
            <div class="p-6 border-b bg-blue-50">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Admin notes</h3>
                <p class="text-gray-700 whitespace-pre-wrap">{{ $contact->admin_notes }}</p>
            </div>
        @endif

        <div class="p-6 space-y-4">
            <h3 class="text-sm font-medium text-gray-700">Admin response</h3>
            <form action="{{ route('admin.support.contacts.respond', $contact) }}" method="POST">
                @csrf
                <textarea name="response" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 mb-4"
                          placeholder="Type your response notes...">{{ old('response', $contact->admin_notes) }}</textarea>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.support.contacts') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Back</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save response</button>
                </div>
            </form>
            <form action="{{ route('admin.support.contacts.resolve', $contact) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Mark resolved</button>
            </form>
            <form action="{{ route('admin.support.contacts.delete', $contact) }}" method="POST" class="inline" onsubmit="return confirm('Delete this message?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection

