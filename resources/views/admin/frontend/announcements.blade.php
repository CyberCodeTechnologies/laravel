@extends('admin.layouts.app')

@section('title', 'Announcements - Panchi Gallery')
@section('header', 'Announcements')

@section('admin_content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Site Announcements</h1>
            <p class="text-gray-600 mt-1">Promotional banners and alert messages</p>
        </div>
        <a href="{{ route('admin.frontend.dashboard') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">Back</a>
    </div>

    <form action="{{ route('admin.frontend.announcements.update') }}" method="POST" class="card-luxury rounded-xl p-6 space-y-4">
        @csrf
        @php
            $items = $announcements->isNotEmpty() ? $announcements : collect([
                (object)['key' => 'main_banner', 'content_en' => '', 'content_my' => null, 'is_active' => false, 'sort_order' => 0],
            ]);
        @endphp
        @foreach($items as $index => $item)
            <div class="p-4 bg-gray-50 rounded-lg space-y-3">
                <input type="hidden" name="announcements[{{ $index }}][key]" value="{{ $item->key }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message (EN) — {{ $item->key }}</label>
                    <textarea name="announcements[{{ $index }}][content_en]" rows="2" required class="w-full border-gray-300 rounded-lg">{{ old("announcements.{$index}.content_en", $item->content_en) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message (MY)</label>
                    <textarea name="announcements[{{ $index }}][content_my]" rows="2" class="w-full border-gray-300 rounded-lg">{{ old("announcements.{$index}.content_my", $item->content_my) }}</textarea>
                </div>
                <div class="flex gap-6 items-center">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="hidden" name="announcements[{{ $index }}][is_active]" value="0">
                        <input type="checkbox" name="announcements[{{ $index }}][is_active]" value="1" {{ old("announcements.{$index}.is_active", $item->is_active) ? 'checked' : '' }}> Active
                    </label>
                    <div>
                        <label class="text-sm text-gray-600 mr-2">Order</label>
                        <input type="number" name="announcements[{{ $index }}][sort_order]" value="{{ old("announcements.{$index}.sort_order", $item->sort_order ?? 0) }}" class="w-20 border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>
        @endforeach
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">Save Announcements</button>
        </div>
    </form>
</div>
@endsection




