{{-- Breadcrumb Navigation Component --}}
{{-- Usage: @include('partials.breadcrumb', ['items' => $breadcrumbItems]) --}}

@if(isset($items) && count($items) > 0)
    <nav class="bg-white border-b border-gray-200" aria-label="Breadcrumb">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                @foreach($items as $index => $item)
                    @if($loop->first)
                        <li>
                            @if(isset($item['url']))
                                <a href="{{ $item['url'] }}" class="hover:text-indigo-600 transition-colors">
                                    @if(isset($item['icon']))
                                        <i class="{{ $item['icon'] }} mr-1"></i>
                                    @endif
                                    {{ $item['title'] }}
                                </a>
                            @else
                                <span class="text-gray-900 font-medium">
                                    @if(isset($item['icon']))
                                        <i class="{{ $item['icon'] }} mr-1"></i>
                                    @endif
                                    {{ $item['title'] }}
                                </span>
                            @endif
                        </li>
                    @elseif($loop->last)
                        <li class="text-gray-900 font-medium">
                            @if(isset($item['icon']))
                                <i class="{{ $item['icon'] }} mr-1"></i>
                            @endif
                            {{ $item['title'] }}
                        </li>
                    @else
                        <li class="flex items-center">
                            <i class="fas fa-chevron-right text-xs text-gray-400 mx-2"></i>
                            @if(isset($item['url']))
                                <a href="{{ $item['url'] }}" class="hover:text-indigo-600 transition-colors">
                                    @if(isset($item['icon']))
                                        <i class="{{ $item['icon'] }} mr-1"></i>
                                    @endif
                                    {{ $item['title'] }}
                                </a>
                            @else
                                <span>
                                    @if(isset($item['icon']))
                                        <i class="{{ $item['icon'] }} mr-1"></i>
                                    @endif
                                    {{ $item['title'] }}
                                </span>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ol>
        </div>
    </nav>
@endif
