@extends('admin.layouts.app')

@section('title', 'Pending Users - Admin')
@section('meta-description', 'Review and approve pending user registrations on Panchi Gallery')

@section('header', 'Pending Users')

@section('admin_content')
<!-- Quick Stats -->
<section class="py-8 bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Pending Users -->
            <div class="bg-yellow-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-yellow-600 font-medium">Pending Users</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ App\Models\User::where('is_approved', false)->count() }}</p>
                        <p class="text-xs text-yellow-700 mt-1">Awaiting approval</p>
                    </div>
                </div>
            </div>

            <!-- Pending Artists -->
            <div class="bg-blue-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-palette text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-blue-600 font-medium">Pending Artists</p>
                        <p class="text-2xl font-bold text-blue-900">{{ App\Models\User::where('role', 'artist')->where('is_approved', false)->count() }}</p>
                        <p class="text-xs text-blue-700 mt-1">Awaiting review</p>
                    </div>
                </div>
            </div>

            <!-- Pending Collectors -->
            <div class="bg-green-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-user text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-green-600 font-medium">Pending Collectors</p>
                        <p class="text-2xl font-bold text-green-900">{{ App\Models\User::where('role', 'collector')->where('is_approved', false)->count() }}</p>
                        <p class="text-xs text-green-700 mt-1">Awaiting approval</p>
                    </div>
                </div>
            </div>

            <!-- Today's Registrations -->
            <div class="bg-purple-50 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-purple-600 font-medium">Today's Registrations</p>
                        <p class="text-2xl font-bold text-purple-900">
                            @php
                                $todayUsers = App\Models\User::where('created_at', '>=', now()->startOfDay())->where('is_approved', false)->count();
                            @endphp
                            {{ $todayUsers }}
                        </p>
                        <p class="text-xs text-purple-700 mt-1">New today</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Actions -->
<section class="py-6 bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <form method="GET" class="flex flex-wrap gap-3 items-center">
                <div class="min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search pending users..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Roles</option>
                        <option value="artist" {{ request('role') == 'artist' ? 'selected' : '' }}>Artist</option>
                        <option value="collector" {{ request('role') == 'collector' ? 'selected' : '' }}>Collector</option>
                    </select>
                </div>
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.users.pending') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </form>
            <div class="flex gap-3">
                <button onclick="bulkApproveAll()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
                    <i class="fas fa-check-double mr-2"></i>Approve All
                </button>
                <a href="{{ route('admin.users') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-users mr-2"></i>All Users
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Pending Users Table -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 border-2 border-white shadow-sm flex items-center justify-center text-indigo-700 font-bold overflow-hidden mr-3">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" class="h-full w-full object-cover">
                                            @else
                                                {{ substr($user->name, 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role === 'artist' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $user->role === 'collector' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           class="text-gray-600 hover:text-gray-900" title="View User">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Approve User" onclick="return confirm('Approve this user?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Reject User" onclick="return confirm('Reject this user? This will delete their account permanently.')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete User" onclick="return confirm('Delete this user? This action cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No pending users</p>
                                        <p class="text-sm">All users have been reviewed and approved.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
function bulkApproveAll() {
    const pendingCount = {{ $users->total() }};
    if (pendingCount === 0) {
        alert('No pending users to approve.');
        return;
    }
    if (!confirm(`Approve all ${pendingCount} pending users?`)) return;

    // Collect all user IDs from the table
    const userIds = [];
    document.querySelectorAll('form[action*="/approve"]').forEach(form => {
        const action = form.getAttribute('action');
        const id = action.split('/').pop();
        userIds.push(id);
    });

    if (userIds.length === 0) {
        alert('No pending users found.');
        return;
    }

    // Submit bulk approval
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('admin.users.bulk-approve') }}';
    form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
    userIds.forEach(id => {
        form.innerHTML += `<input type="hidden" name="user_ids[]" value="${id}">`;
    });
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
@endsection
