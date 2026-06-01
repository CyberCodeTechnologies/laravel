@extends('admin.layouts.app')

@section('title', 'Navigation Menu - Panchi Gallery')
@section('header', 'Navigation Menu')

@section('admin_content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Navigation Menu</h1>
            <p class="text-gray-600 mt-1">Manage main menu items, order, and visibility</p>
        </div>
        <a href="{{ route('admin.frontend.dashboard') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">Back</a>
    </div>

    <form action="{{ route('admin.frontend.navigation.update') }}" method="POST" class="card-luxury rounded-xl p-6 space-y-4">
        @csrf
        @php
            $defaults = [
                ['key' => 'nav_home', 'content_en' => 'Home', 'sort_order' => 0],
                ['key' => 'nav_artworks', 'content_en' => 'Artworks', 'sort_order' => 1],
                ['key' => 'nav_artists', 'content_en' => 'Artists', 'sort_order' => 2],
                ['key' => 'nav_exhibitions', 'content_en' => 'Exhibitions', 'sort_order' => 3],
                ['key' => 'nav_blog', 'content_en' => 'Blog', 'sort_order' => 4],
                ['key' => 'nav_marketplace', 'content_en' => 'Marketplace', 'sort_order' => 5],
                ['key' => 'nav_orders', 'content_en' => 'Orders', 'sort_order' => 6],
                ['key' => 'nav_about', 'content_en' => 'About', 'sort_order' => 7],
                ['key' => 'nav_contact', 'content_en' => 'Contact', 'sort_order' => 8],
            ];
            
            // Get existing items
            $existingKeys = $menuItems->pluck('key')->toArray();
            
            // Filter defaults to only those not already in $menuItems
            $missingDefaults = collect($defaults)->filter(function($d) use ($existingKeys) {
                return !in_array($d['key'], $existingKeys);
            })->map(fn ($d) => (object) array_merge($d, ['content_my' => null, 'is_active' => true]));
            
            // Combine existing items with missing defaults
            $items = $menuItems->concat($missingDefaults)->sortBy('sort_order');
        @endphp

        @foreach($items as $index => $item)
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-lg items-end">
                <input type="hidden" name="menu_items[{{ $index }}][key]" value="{{ $item->key }}">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key</label>
                    <input type="text" value="{{ $item->key }}" disabled class="w-full border-gray-300 rounded-lg bg-white">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label (EN)</label>
                    <input type="text" name="menu_items[{{ $index }}][content_en]" value="{{ old("menu_items.{$index}.content_en", $item->content_en) }}" required class="w-full border-gray-300 rounded-lg">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label (MY)</label>
                    <input type="text" name="menu_items[{{ $index }}][content_my]" value="{{ old("menu_items.{$index}.content_my", $item->content_my) }}" class="w-full border-gray-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                    <input type="number" name="menu_items[{{ $index }}][sort_order]" value="{{ old("menu_items.{$index}.sort_order", $item->sort_order ?? $index) }}" class="w-full border-gray-300 rounded-lg">
                </div>
                <div class="md:col-span-1">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="hidden" name="menu_items[{{ $index }}][is_active]" value="0">
                        <input type="checkbox" name="menu_items[{{ $index }}][is_active]" value="1" {{ old("menu_items.{$index}.is_active", $item->is_active ?? true) ? 'checked' : '' }}>
                        Active
                    </label>
                </div>
            </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">Save Navigation</button>
        </div>
    </form>
</div>
@endsection

