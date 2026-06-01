<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">From</p>
            <p class="font-medium text-gray-900">{{ $contact->name }}</p>
            <p class="text-sm text-gray-600">{{ $contact->email }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Received</p>
            <p class="font-medium text-gray-900">{{ $contact->created_at->format('M j, Y g:i A') }}</p>
            <p class="text-sm mt-1">
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                    {{ $contact->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $contact->status === 'responded' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $contact->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}">
                    {{ ucfirst($contact->status) }}
                </span>
            </p>
        </div>
    </div>

    <div>
        <p class="text-sm text-gray-500 mb-1">Subject</p>
        <p class="font-medium text-gray-900">{{ $contact->subject }}</p>
    </div>

    <div>
        <p class="text-sm text-gray-500 mb-1">Message</p>
        <p class="text-gray-700 whitespace-pre-wrap">{{ $contact->message }}</p>
    </div>

    @if($contact->admin_notes)
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-500 mb-1">Admin notes</p>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $contact->admin_notes }}</p>
        </div>
    @endif

    <div class="flex gap-3 pt-2">
        <a href="{{ route('admin.support.contacts.show', $contact) }}"
           class="text-sm text-blue-600 hover:text-blue-800">Open full view</a>
        <form action="{{ route('admin.support.contacts.resolve', $contact) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-sm text-green-600 hover:text-green-800">Mark resolved</button>
        </form>
    </div>
</div>
