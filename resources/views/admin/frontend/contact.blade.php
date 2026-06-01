@extends('admin.layouts.app')

@section('title', 'Contact Information - Panchi Gallery')
@section('header', 'Contact Information')

@section('admin_content')
@php
    $val = fn ($key, $default = '') => $contactInfo['contact_'.$key]->value ?? $contactInfo[$key]->value ?? $default;
@endphp
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Contact Information</h1>
            <p class="text-gray-600 mt-1">Business contact details shown on the public site</p>
        </div>
        <a href="{{ route('admin.frontend.dashboard') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">Back</a>
    </div>

    <form action="{{ route('admin.frontend.contact.update') }}" method="POST" class="card-luxury rounded-xl p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="contact[email]" value="{{ old('contact.email', $val('email', 'info@panchigallery.com')) }}" required class="w-full border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
            <input type="text" name="contact[phone]" value="{{ old('contact.phone', $val('phone')) }}" class="w-full border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address (EN)</label>
            <textarea name="contact[address]" rows="2" class="w-full border-gray-300 rounded-lg">{{ old('contact.address', $val('address')) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address (MY)</label>
            <textarea name="contact[address_my]" rows="2" class="w-full border-gray-300 rounded-lg">{{ old('contact.address_my', $val('address_my')) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Business Hours</label>
            <input type="text" name="contact[business_hours]" value="{{ old('contact.business_hours', $val('business_hours', 'Mon–Fri 9:00–18:00')) }}" class="w-full border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Map Embed Code</label>
            <textarea name="contact[map_embed]" rows="3" class="w-full border-gray-300 rounded-lg font-mono text-sm">{{ old('contact.map_embed', $val('map_embed')) }}</textarea>
        </div>
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">Save Contact Info</button>
        </div>
    </form>
</div>
@endsection




