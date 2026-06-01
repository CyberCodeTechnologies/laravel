@props([
    'artist' => null,
    'compact' => false,
    'showFollow' => true
])

@if(!$artist)
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="flex items-center p-4">
            <div class="skeleton w-16 h-16 rounded-full mr-4 bg-gray-200"></div>
            <div class="flex-1">
                <div class="skeleton h-4 mb-2 bg-gray-200"></div>
                <div class="skeleton h-3 w-3/4 bg-gray-200"></div>
            </div>
        </div>
    </div>
@else
    <div class="group bg-white border border-gray-200 rounded-2xl overflow-hidden hover:border-black/20 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 {{ $compact ? 'compact' : '' }}">
        @if(!$compact)
            <!-- Cover Image -->
            <div class="relative h-40 overflow-hidden">
                <img 
                    src="{{ $artist->cover_image_url ?? asset('images/placeholder-artist-cover.jpg') }}" 
                    alt="{{ $artist->name }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>
                
                @if($artist->verified ?? false)
                    <div class="absolute top-3 right-3">
                        <div class="bg-black/20 backdrop-blur-md px-3 py-1 rounded-full border border-black/30">
                            <span class="text-white text-xs font-medium flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Verified</span>
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Profile Image Overlap -->
            <div class="px-5 -mt-16">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full blur opacity-25 group-hover:opacity-50 transition-opacity duration-300"></div>
                    <img 
                        src="{{ $artist->avatar_url }}" 
                        alt="{{ $artist->name }}"
                        class="relative w-28 h-28 rounded-full border-4 border-white object-cover shadow-xl group-hover:scale-105 transition-transform duration-300"
                    >
                </div>
            </div>
        @else
            <!-- Compact Version -->
            <div class="flex items-center p-4">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full blur opacity-25"></div>
                    <img 
                        src="{{ $artist->avatar_url }}" 
                        alt="{{ $artist->name }}"
                        class="relative w-14 h-14 rounded-full border-3 border-white object-cover"
                    >
                </div>
            </div>
        @endif
        
        <!-- Content -->
        <div class="{{ $compact ? 'flex-1 ml-4' : 'px-5 pb-5 mt-3' }}">
            <div class="flex items-start justify-between">
                <div class="{{ $compact ? 'flex-1' : '' }}">
                    <h3 class="font-serif text-xl font-bold text-gray-900 mb-1 group-hover:text-black transition-colors">
                        <a href="{{ route('public.artists.show', $artist->slug ?? $artist->id) }}" class="hover:underline">
                            {{ $artist->name }}
                        </a>
                    </h3>
                    
                    <p class="text-sm text-gray-600 mb-2">
                        {{ $artist->specialization ?? __('messages.visual_artist') }}
                    </p>
                    
                    @if(!$compact)
                        <p class="text-sm text-gray-500 line-clamp-2 mb-4 leading-relaxed">
                            {{ $artist->bio ?? __('messages.artist_bio_default') }}
                        </p>
                        
                        <!-- Stats -->
                        <div class="flex items-center space-x-4 text-sm mb-4">
                            <div class="flex items-center space-x-1 text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $artist->artworks_count ?? 0 }}</span>
                            </div>
                            <div class="flex items-center space-x-1 text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>{{ $artist->followers_count ?? 0 }}</span>
                            </div>
                            @if($artist->location)
                                <div class="flex items-center space-x-1 text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $artist->location }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                
                @if($showFollow && auth()->check() && auth()->id() !== $artist->id)
                    <div class="{{ $compact ? 'ml-4' : '' }}">
                        <button 
                            onclick="toggleFollow({{ $artist->id }})"
                            class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 {{ auth()->user()->isFollowing($artist) 
                                ? 'bg-gray-100 text-gray-900 border border-gray-200 hover:bg-gray-200' 
                                : 'bg-black text-white hover:bg-gray-800 shadow-lg' }}"
                        >
                            {{ auth()->user()->isFollowing($artist) ? __('messages.following') : __('messages.follow') }}
                        </button>
                    </div>
                @endif
            </div>
            
            @if(!$compact)
                <!-- Social Links -->
                @if($artist->social_links ?? null)
                    <div class="flex space-x-3 pt-4 border-t border-gray-100">
                        @if(($artist->social_links['instagram'] ?? null) && is_string($artist->social_links['instagram']))
                            <a href="{{ $artist->social_links['instagram'] }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-200 text-gray-400 hover:bg-gray-100 hover:border-gray-300 hover:text-gray-600 transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1112.324 0 6.162 6.162 0 01-12.324 0zM12 16a4 4 0 110-8 4 4 0 010 8zm4.965-10.405a1.44 1.44 0 112.881.001 1.44 1.44 0 01-2.881-.001z"/>
                                </svg>
                            </a>
                        @endif
                        @if(($artist->social_links['facebook'] ?? null) && is_string($artist->social_links['facebook']))
                            <a href="{{ $artist->social_links['facebook'] }}" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-50 border border-gray-200 text-gray-400 hover:bg-gray-100 hover:border-gray-300 hover:text-gray-600 transition-all duration-300">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>
@endif
