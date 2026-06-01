@props([
    'type' => null,
    'data' => [],
])

@if($type && $data)
    @php
        $schema = match($type) {
            'organization' => \App\Helpers\SchemaHelper::organization(),
            'artwork' => \App\Helpers\SchemaHelper::artwork($data),
            'artist' => \App\Helpers\SchemaHelper::artist($data),
            'collection' => \App\Helpers\SchemaHelper::collection($data),
            'blog' => \App\Helpers\SchemaHelper::blogPost($data),
            'product' => \App\Helpers\SchemaHelper::product($data),
            'localBusiness' => \App\Helpers\SchemaHelper::localBusiness(),
            'breadcrumb' => \App\Helpers\SchemaHelper::breadcrumb($data),
            default => null,
        };
    @endphp

    @if($schema)
        {!! \App\Helpers\SchemaHelper::render($schema) !!}
    @endif
@endif
