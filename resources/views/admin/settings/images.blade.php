@extends('admin.layouts.app')

@section('title', 'Images Settings - Admin')
@section('header', 'Images Settings')

@push('styles')
<style>
.image-upload-card {
    transition: all 0.3s ease;
}
.image-upload-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.image-preview-container {
    position: relative;
    overflow: hidden;
    background: #f3f4f6;
    height: 160px;
}
.image-preview-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.image-upload-card:hover .image-preview-container img {
    transform: scale(1.02);
}
.sync-status {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
}
.sync-status.success { background-color: #dcfce7; color: #166534; }
.sync-status.warning { background-color: #fef3c7; color: #92400e; }
.sync-status.error { background-color: #fee2e2; color: #991b1b; }
.category-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem 0.5rem 0 0;
}
.empty-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #9ca3af;
    font-size: 0.875rem;
}
</style>
@endpush

@section('admin_content')
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Frontend Images Management</h1>
            <p class="text-gray-600 mt-1">Manage all logos, banners, hero images, and other visual assets used throughout the website.</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-images text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Images</p>
                        <p class="text-2xl font-bold">{{ $groupedSettings->flatten()->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Configured</p>
                        <p class="text-2xl font-bold">{{ $groupedSettings->flatten()->whereNotNull('value')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Missing</p>
                        <p class="text-2xl font-bold">{{ $groupedSettings->flatten()->whereNull('value')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-folder text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Categories</p>
                        <p class="text-2xl font-bold">{{ $groupedSettings->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Settings Form -->
        <form method="POST" action="{{ route('admin.settings.images.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @forelse($groupedSettings as $group => $settings)
                @if(isset($imageCategories[$group]))
                    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
                        <div class="category-header">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas {{ $imageCategories[$group]['icon'] }} text-2xl mr-3"></i>
                                    <div>
                                        <h2 class="text-lg font-semibold">{{ $imageCategories[$group]['title'] }}</h2>
                                        <p class="text-sm opacity-90">{{ $imageCategories[$group]['description'] }}</p>
                                    </div>
                                </div>
                                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                                    {{ $settings->count() }} image(s)
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($settings as $setting)
                                    <div class="image-upload-card border rounded-lg p-4 bg-white">
                                        <div class="flex justify-between items-start mb-3">
                                            <label class="font-medium text-gray-900">{{ $setting->label ?? str_replace('_', ' ', $setting->key) }}</label>
                                            <div class="sync-status" id="sync-status-{{ $setting->key }}">
                                                <i class="fas fa-circle-notch fa-spin"></i>
                                                <span>Checking...</span>
                                            </div>
                                        </div>

                                        <!-- Image Preview -->
                                        <div class="image-preview-container rounded-lg mb-3">
                                            @if($setting->image_url)
                                                <img src="{{ $setting->image_url }}" alt="{{ $setting->label }}" class="rounded-lg">
                                            @else
                                                <div class="empty-image-placeholder">
                                                    <div class="text-center">
                                                        <i class="fas fa-image text-4xl mb-2"></i>
                                                        <p>No image uploaded</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- File Input -->
                                        <input type="file"
                                               name="settings[{{ $setting->key }}][file]"
                                               accept="image/*"
                                               class="w-full text-sm border rounded px-3 py-2"
                                               onchange="previewImage(this, '{{ $setting->key }}')">
                                        <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">

                                        <!-- Recommendations -->
                                        @if($setting->description)
                                            <p class="text-xs text-gray-500 mt-2">{{ $setting->description }}</p>
                                        @endif

                                        <!-- Optimization Suggestions Container -->
                                        <div id="optimization-{{ $setting->key }}" class="mt-2"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-3"></i>
                    <h3 class="text-lg font-medium text-yellow-900">No Image Settings Found</h3>
                    <p class="text-yellow-700 mt-1">Please run the database seeder to create image settings.</p>
                    <code class="block bg-yellow-100 rounded p-2 mt-3 text-sm">php artisan db:seed --class=GeneralSettingsSeeder</code>
                </div>
            @endforelse

            <!-- Action Buttons -->
            @if($groupedSettings->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6 sticky bottom-4 z-10">
                    <div class="flex flex-wrap gap-4 justify-between items-center">
                        <div class="flex gap-2">
                            <button type="button"
                                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition"
                                    onclick="checkAllSyncStatus()">
                                <i class="fas fa-sync-alt mr-2"></i>Check All Status
                            </button>
                            <button type="button"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition"
                                    onclick="syncAllImages()">
                                <i class="fas fa-cloud-upload-alt mr-2"></i>Sync All Images
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <button type="button"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition"
                                    onclick="resetForm()">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </button>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                                <i class="fas fa-save mr-2"></i>Save All Changes
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
</section>

<script>
function previewImage(input, key) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const card = input.closest('.image-upload-card');
            const container = card.querySelector('.image-preview-container');
            container.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="rounded-lg">';

            // Update status to pending
            const statusEl = document.getElementById('sync-status-' + key);
            if (statusEl) {
                statusEl.className = 'sync-status warning';
                statusEl.innerHTML = '<i class="fas fa-upload"></i><span>Ready to upload</span>';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function checkSyncStatus(key) {
    const statusEl = document.getElementById('sync-status-' + key);
    if (!statusEl) return;

    statusEl.className = 'sync-status';
    statusEl.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i><span>Checking...</span>';

    const checkUrl = '{{ route('admin.settings.image.optimization') }}';

    fetch(checkUrl + '?key=' + key)
        .then(response => response.json())
        .then(data => {
            if (data.suggestions && data.suggestions.length > 0) {
                statusEl.className = 'sync-status warning';
                statusEl.innerHTML = '<i class="fas fa-exclamation-triangle"></i><span>Needs Optimization</span>';

                // Show suggestions
                const optContainer = document.getElementById('optimization-' + key);
                if (optContainer) {
                    optContainer.innerHTML = '<div class="bg-yellow-50 border border-yellow-200 rounded p-2 text-xs text-yellow-800"><ul class="list-disc list-inside">' + data.suggestions.map(s => '<li>' + s + '</li>').join('') + '</ul></div>';
                }
            } else {
                statusEl.className = 'sync-status success';
                statusEl.innerHTML = '<i class="fas fa-check-circle"></i><span>OK</span>';
            }
        })
        .catch(error => {
            statusEl.className = 'sync-status error';
            statusEl.innerHTML = '<i class="fas fa-times-circle"></i><span>Error</span>';
        });
}

function checkAllSyncStatus() {
    const keys = @json($groupedSettings->flatten()->pluck('key'));
    keys.forEach(key => checkSyncStatus(key));
}

function syncAllImages() {
    const button = event.target.closest('button');
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Syncing...';

    const syncUrl = '{{ route('admin.settings.sync.images') }}';

    fetch(syncUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Image sync completed! ' + data.summary.successful + '/' + data.summary.total + ' images synchronized.');
            checkAllSyncStatus();
        } else {
            showAlert('error', data.message || 'Sync failed');
        }
    })
    .catch(error => {
        showAlert('error', 'Network error during sync');
        console.error('Sync error:', error);
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalHtml;
    });
}

function resetForm() {
    if (confirm('Clear all unsaved image selections?')) {
        document.querySelectorAll('input[type="file"]').forEach(input => input.value = '');
        checkAllSyncStatus();
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ' + (type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white');
    alertDiv.innerHTML = '<div class="flex items-center"><i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' mr-2"></i><span>' + message + '</span></div>';
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkAllSyncStatus();
});
</script>
@endsection
