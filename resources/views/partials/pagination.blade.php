{{-- Custom Pagination Component --}}
{{-- Usage: @include('partials.pagination', ['items' => $items]) --}}

@if($items->hasPages())
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
            Showing 
            <span class="font-medium">{{ $items->firstItem() }}</span>
            to 
            <span class="font-medium">{{ $items->lastItem() }}</span>
            of 
            <span class="font-medium">{{ $items->total() }}</span>
            results
        </div>
        
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            {{-- Previous Page Link --}}
            @if($items->onFirstPage())
                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $items->previousPageUrl() }}" 
                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif
            
            {{-- Pagination Elements --}}
            @foreach($elements = $items->links() as $element)
                {{-- "Three Dots" Separator --}}
                @if(is_string($element))
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                        {{ $element }}
                    </span>
                @endif
                
                {{-- Array Of Links --}}
                @if(is_array($element))
                    @foreach($element as $page => $url)
                        @if($page == $items->currentPage())
                            <span aria-current="page" 
                                  class="relative inline-flex items-center px-4 py-2 border border-indigo-500 bg-indigo-50 text-sm font-medium text-indigo-600">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
            
            {{-- Next Page Link --}}
            @if($items->hasMorePages())
                <a href="{{ $items->nextPageUrl() }}" 
                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-500 cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </nav>
    </div>
@endif
