@extends('admin.layouts.app')

@section('title', 'Social Media - Panchi Gallery')
@section('header', 'Social Media Links')

@section('admin_content')
@php
    $links = $socialLinks->keyBy(fn ($s) => str_replace('social_', '', $s->key));
    $platforms = ['facebook', 'instagram', 'twitter', 'youtube', 'linkedin', 'pinterest'];
@endphp
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Social Media</h1>
            <p class="text-gray-600 mt-1">Links shown in the site header and footer</p>
        </div>
        <a href="{{ route('admin.frontend.dashboard') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">Back</a>
    </div>

    <form action="{{ route('admin.frontend.social.update') }}" method="POST" class="card-luxury rounded-xl p-6 space-y-4">
        @csrf
        @foreach($platforms as $platform)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 capitalize">{{ $platform }} URL</label>
                <input type="url" name="social[{{ $platform }}]" value="{{ old("social.{$platform}", $links[$platform]->value ?? '') }}" placeholder="https://" class="w-full border-gray-300 rounded-lg">
            </div>
        @endforeach
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">Save Social Links</button>
        </div>
    </form>
</div>
@endsection
