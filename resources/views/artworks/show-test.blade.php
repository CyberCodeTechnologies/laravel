@extends('layouts.app')

@section('title', 'Test Artwork - Panchi Gallery')
@section('meta-description', 'Test description')

@section('content')
<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">Test Artwork Page</h1>
        <p>This is a test to verify the template structure works.</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    console.log('Test page loaded');
</script>
@endpush
