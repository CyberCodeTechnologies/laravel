@extends('admin.layouts.app')

@section('title', 'Footer Content - Panchi Gallery')
@section('header', 'Footer Content')

@section('admin_content')
@php
    $allFooter = collect($footerColumns)->flatten()->merge($footerBottom);
    if ($allFooter->isEmpty()) {
        $allFooter = collect([
            (object)['key' => 'footer_tagline', 'value' => 'Panchi Gallery — Myanmar Art', 'value_my' => null],
            (object)['key' => 'footer_copyright', 'value' => '© '.date('Y').' Panchi Gallery', 'value_my' => null],
        ]);
    }
@endphp
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Footer Content</h1>
            <p class="text-gray-600 mt-1">Manage footer text and links</p>
        </div>
        <a href="{{ route('admin.frontend.dashboard') }}" class="btn-elegant px-4 py-2 rounded-lg text-sm">Back</a>
    </div>

    <form action="{{ route('admin.frontend.footer.update') }}" method="POST" class="card-luxury rounded-xl p-6 space-y-4">
        @csrf
        @foreach($allFooter as $index => $setting)
            <div class="p-4 bg-gray-50 rounded-lg space-y-2">
                <label class="block text-sm font-medium text-gray-700">{{ $setting->key }}</label>
                <input type="hidden" name="footer[{{ $index }}][key]" value="{{ $setting->key }}">
                <input type="text" name="footer[{{ $index }}][value]" value="{{ old("footer.{$index}.value", $setting->value) }}" class="w-full border-gray-300 rounded-lg">
                <input type="text" name="footer[{{ $index }}][value_my]" value="{{ old("footer.{$index}.value_my", $setting->value_my) }}" placeholder="Myanmar (optional)" class="w-full border-gray-300 rounded-lg">
            </div>
        @endforeach
        <div class="flex justify-end pt-4">
            <button type="submit" class="btn-luxury px-6 py-3 rounded-lg text-white font-medium">Save Footer</button>
        </div>
    </form>
</div>
@endsection




