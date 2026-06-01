@extends('layouts.app')

@section('title', 'Followers - Panchi Gallery')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-gray-900">Followers</h1>
            <p class="text-gray-600 mt-2">People following your work</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(isset($followers) && count($followers) > 0)
            <div class="bg-white rounded-lg shadow-sm">
                <div class="divide-y divide-gray-100">
                    @foreach($followers as $follower)
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                    @if($follower->follower->avatar ?? false)
                                        <img src="{{ $follower->follower->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-gray-400 text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $follower->follower->name ?? 'User' }}</h3>
                                    <p class="text-sm text-gray-500">Following since {{ $follower->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <a href="mailto:{{ $follower->follower->email ?? '' }}" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
                
                @if(method_exists($followers, 'links'))
                    <div class="p-4 border-t border-gray-100">
                        {{ $followers->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No followers yet</h3>
                <p class="text-gray-500">Share your profile to start building your audience.</p>
            </div>
        @endif
    </div>
</div>
@endsection
