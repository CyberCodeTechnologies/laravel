@props([
    'id' => null,
    'title' => null,
    'size' => 'md',
    'showCloseButton' => true,
    'closeOnBackdrop' => true
])

@php
    if (!$id) {
        $id = 'modal-' . uniqid();
    }
    
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-2xl',
        'lg' => 'max-w-4xl',
        'xl' => 'max-w-6xl',
        'full' => 'max-w-full mx-4',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<!-- Modal Backdrop -->
<div id="{{ $id }}" class="fixed inset-0 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        @if($closeOnBackdrop)
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="closeModal('{{ $id }}')"></div>
        @else
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"></div>
        @endif

        <!-- Modal Panel -->
        <div class="inline-block w-full overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg {{ $sizeClass }}">
            <!-- Header -->
            @if($title || $showCloseButton)
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    @if($title)
                        <h3 class="text-lg font-semibold text-gray-900 font-serif">{{ $title }}</h3>
                    @else
                        <div></div>
                    @endif
                    
                    @if($showCloseButton)
                        <button 
                            onclick="closeModal('{{ $id }}')"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif
            
            <!-- Body -->
            <div class="px-6 py-4">
                {{ $slot }}
            </div>
            
            <!-- Footer (optional) -->
            @if(isset($footer))
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id^="modal-"]:not(.hidden)');
        modals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});
</script>
