<!-- Professional Enterprise Loader -->
<div id="global-loader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern)"/>
        </svg>
    </div>

    <!-- Loader Content -->
    <div class="relative z-10 flex flex-col items-center">
        <!-- Animated Logo -->
        <div class="mb-8">
            <div class="relative w-24 h-24">
                <!-- Outer Ring -->
                <div class="absolute inset-0 border-4 border-gray-200 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-t-black border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin"></div>
                
                <!-- Inner Ring -->
                <div class="absolute inset-2 border-3 border-gray-200 rounded-full"></div>
                <div class="absolute inset-2 border-3 border-t-gray-800 border-r-transparent border-b-transparent border-l-transparent rounded-full animate-spin" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
                
                <!-- Center Logo Icon -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Loading Text -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">
                <span class="inline-block animate-pulse">Panchi</span>
                <span class="inline-block animate-pulse" style="animation-delay: 0.1s;">Gallery</span>
            </h2>
            <p class="text-sm text-gray-500 font-medium tracking-wide uppercase">Loading Experience</p>
        </div>

        <!-- Progress Bar -->
        <div class="mt-8 w-64 h-1 bg-gray-200 rounded-full overflow-hidden">
            <div id="loader-progress" class="h-full bg-gradient-to-r from-gray-800 via-gray-600 to-gray-800 rounded-full transition-all duration-300 ease-out" style="width: 0%;"></div>
        </div>

        <!-- Loading Dots -->
        <div class="mt-6 flex space-x-2">
            <div class="w-2 h-2 bg-gray-800 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
            <div class="w-2 h-2 bg-gray-600 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
        </div>
    </div>

    <!-- Fade Overlay -->
    <div id="loader-fade" class="absolute inset-0 bg-white transition-opacity duration-500 opacity-100 pointer-events-none"></div>
</div>

<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-8px);
        }
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    .animate-bounce {
        animation: bounce 0.6s ease-in-out infinite;
    }
    
    .animate-pulse {
        animation: pulse 2s ease-in-out infinite;
    }
</style>
