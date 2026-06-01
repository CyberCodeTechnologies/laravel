@extends('admin.layouts.app')

@section('title', 'View User - Admin - Panchi Gallery')
@section('header', 'View User Details')

@section('admin_content')
<div class="max-w-6xl mx-auto py-4">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.users') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to Users
        </a>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg"
                         src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=128' }}"
                         alt="{{ $user->name }}">
                    <div class="ml-5">
                        <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-sm text-blue-100">{{ $user->email }}</p>
                        <div class="mt-2 flex gap-2 flex-wrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $user->role === 'artist' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $user->role === 'collector' ? 'bg-green-100 text-green-800' : '' }}">
                                <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : ($user->role === 'artist' ? 'palette' : 'shopping-bag') }} mr-1"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->is_verified)
                                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-check-circle mr-1"></i> Verified
                                </span>
                            @endif
                            @if($user->status === 'approved')
                                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Approved
                                </span>
                            @elseif($user->status === 'rejected')
                                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i> Rejected
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-blue-100">User ID</p>
                    <p class="text-lg font-semibold text-white">#{{ $user->id }}</p>
                    <p class="text-xs text-blue-100 mt-1">Joined</p>
                    <p class="text-sm font-semibold text-white">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-6 py-4 bg-gray-50 border-b">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if($user->role === 'artist')
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Artworks</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $user->artworks()->count() }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Sales</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $user->sales()->count() }}</p>
                    </div>
                @elseif($user->role === 'collector')
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Purchases</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $user->purchases()->count() }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Collection</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $user->currentArtworks()->count() }}</p>
                    </div>
                @endif
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Followers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->followers()->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Following</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->following()->count() }}</p>
                </div>
            </div>
        </div>

        <!-- User Details -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Account Information -->
                <div class="space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b pb-2 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Account Information
                    </h3>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Name</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->name }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Email</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->email }}</p>
                    </div>

                    @if($user->phone)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Phone</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $user->phone }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Role</p>
                        <p class="text-sm text-gray-900 font-medium">{{ ucfirst($user->role) }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Status</p>
                        @if($user->status === 'approved')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Approved
                            </span>
                        @elseif($user->status === 'rejected')
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Rejected
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Activity Information -->
                <div class="space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b pb-2 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                        Activity Information
                    </h3>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Joined Date</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Last Login</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $user->last_login_at?->format('M d, Y H:i') ?? 'Never' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Email Verified</p>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Yes ({{ $user->email_verified_at->format('M d, Y') }})
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> No
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Account Active</p>
                        @if($user->is_active ?? true)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Inactive
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 border-b pb-2 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>
                        Contact Information
                    </h3>

                    @if($user->address)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Address</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $user->address }}</p>
                        </div>
                    @endif

                    @if($user->city)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">City</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $user->city }}</p>
                        </div>
                    @endif

                    @if($user->country)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Country</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $user->country }}</p>
                        </div>
                    @endif

                    @if($user->location)
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Location</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $user->location }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Artist Specific Information -->
            @if($user->role === 'artist' && ($user->bio || $user->specialization || $user->years_active))
                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-palette mr-2 text-blue-600"></i>
                        Artist Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($user->specialization)
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Specialization</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $user->specialization }}</p>
                            </div>
                        @endif
                        @if($user->years_active)
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Years Active</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $user->years_active }} years</p>
                            </div>
                        @endif
                        @if($user->bio)
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Bio</p>
                                <p class="text-sm text-gray-900 font-medium">{{ $user->bio }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="bg-gray-50 px-6 py-4 border-t flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex flex-wrap gap-2">
                @if($user->status === 'pending')
                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-2" onclick="return confirm('Approve this user?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-red-700 transition flex items-center gap-2" onclick="return confirm('Reject this user? This action cannot be undone.')">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    </form>
                @elseif($user->status === 'rejected')
                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-green-700 transition flex items-center gap-2" onclick="return confirm('Approve this user?')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                @endif
                @if(!$user->email_verified_at)
                    <form method="POST" action="{{ route('admin.users.resend-verification', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <i class="fas fa-envelope"></i> Resend Verification
                        </button>
                    </form>
                @endif
                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="{{ $user->is_active ?? true ? 'bg-amber-600 hover:bg-amber-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 text-sm rounded-lg transition flex items-center gap-2" onclick="return confirm('{{ $user->is_active ?? true ? 'Suspend this user account?' : 'Activate this user account?' }}')">
                            <i class="fas fa-{{ $user->is_active ?? true ? 'pause' : 'play' }}"></i> {{ $user->is_active ?? true ? 'Suspend' : 'Activate' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-purple-700 transition flex items-center gap-2" onclick="return confirm('Impersonate this user? You will be logged in as them.')">
                            <i class="fas fa-user-secret"></i> Impersonate
                        </button>
                    </form>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-indigo-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 text-sm rounded-lg hover:bg-red-700 transition flex items-center gap-2" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
