

<?php $__env->startSection('title', 'General Settings - Admin'); ?>
<?php $__env->startSection('header', 'General Settings'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.image-sync-status {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
}
.image-sync-status.success {
    background-color: #dcfce7;
    color: #166534;
}
.image-sync-status.warning {
    background-color: #fef3c7;
    color: #92400e;
}
.image-sync-status.error {
    background-color: #fee2e2;
    color: #991b1b;
}
.image-preview-container {
    position: relative;
    display: inline-block;
}
.image-preview-container img {
    max-width: 200px;
    max-height: 100px;
    object-fit: contain;
}
.optimization-suggestions {
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: #6b7280;
}
.sync-button {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.2s;
}
.sync-button:hover {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    transform: translateY(-1px);
}
.sync-button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('admin_content'); ?>
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search and Filter Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative w-full md:w-96">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="settingSearch" placeholder="Search settings..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="expandAllSections()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-expand-alt mr-2"></i>Expand All
                    </button>
                    <button type="button" onclick="collapseAllSections()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-compress-alt mr-2"></i>Collapse All
                    </button>
                </div>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('admin.settings.general.update')); ?>" enctype="multipart/form-data" class="space-y-6" id="settingsForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <?php $__currentLoopData = $groupedSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $settings): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6 border-b flex justify-between items-center">
                        <h2 class="text-xl font-semibold capitalize"><?php echo e(str_replace('_', ' ', $group)); ?></h2>
                        <?php if($group === 'branding'): ?>
                            <button type="button" class="sync-button" onclick="syncAllImages()">
                                <i class="fas fa-sync-alt mr-2"></i>Sync Images
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="p-6 space-y-6">
                        <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-b pb-6">
                                <?php if($setting->type === 'image'): ?>
                                    <div class="space-y-3">
                                        <div class="flex justify-between items-center">
                                            <label class="block text-sm font-medium"><?php echo e($setting->label ?? $setting->key); ?></label>
                                            <div class="image-sync-status" id="sync-status-<?php echo e($setting->key); ?>">
                                                <i class="fas fa-circle-notch fa-spin"></i>
                                                <span>Checking...</span>
                                            </div>
                                        </div>

                                        <?php if($setting->value && $setting->image_url): ?>
                                            <div class="image-preview-container">
                                                <img src="<?php echo e($setting->image_url); ?>" class="rounded border bg-gray-100" alt="<?php echo e($setting->label); ?>">
                                            </div>
                                        <?php endif; ?>

                                        <input type="file" name="settings[<?php echo e($setting->key); ?>][file]" accept="image/*" class="w-full" onchange="previewImage(this, '<?php echo e($setting->key); ?>')">
                                        <input type="hidden" name="settings[<?php echo e($setting->key); ?>][key]" value="<?php echo e($setting->key); ?>">
                                        <input type="hidden" name="settings[<?php echo e($setting->key); ?>][value]" value="<?php echo e($setting->value); ?>">

                                        <div id="optimization-<?php echo e($setting->key); ?>" class="optimization-suggestions"></div>

                                        <?php if($setting->description): ?>
                                            <p class="text-xs text-gray-500"><?php echo e($setting->description); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif($setting->type === 'select'): ?>
                                    <label class="block text-sm font-medium mb-2"><?php echo e($setting->label ?? $setting->key); ?></label>
                                    <?php
                                        $selectOptions = match($setting->key) {
                                            'backup_frequency' => ['hourly' => 'Hourly', 'daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'],
                                            'cache_driver' => ['file' => 'File', 'database' => 'Database', 'redis' => 'Redis', 'memcached' => 'Memcached'],
                                            'default_language' => ['en' => 'English', 'my' => 'Myanmar (Burmese)'],
                                            default => []
                                        };
                                    ?>
                                    <select name="settings[<?php echo e($setting->key); ?>][value]" class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <?php $__currentLoopData = $selectOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionValue => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($optionValue); ?>" <?php echo e($setting->value == $optionValue ? 'selected' : ''); ?>><?php echo e($optionLabel); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <input type="hidden" name="settings[<?php echo e($setting->key); ?>][key]" value="<?php echo e($setting->key); ?>">
                                    <?php if($setting->description): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                                    <?php endif; ?>
                                <?php elseif($setting->type === 'json'): ?>
                                    <label class="block text-sm font-medium mb-2"><?php echo e($setting->label ?? $setting->key); ?></label>
                                    <textarea name="settings[<?php echo e($setting->key); ?>][value]" rows="4" class="w-full px-3 py-2 border rounded font-mono text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder='["value1", "value2"] or {"key": "value"}'><?php echo e($setting->value); ?></textarea>
                                    <input type="hidden" name="settings[<?php echo e($setting->key); ?>][key]" value="<?php echo e($setting->key); ?>">
                                    <?php if($setting->description): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-blue-500 mt-1"><i class="fas fa-info-circle mr-1"></i>Enter valid JSON format</p>
                                <?php elseif($setting->type === 'textarea'): ?>
                                    <label class="block text-sm font-medium mb-2"><?php echo e($setting->label ?? $setting->key); ?></label>
                                    <textarea name="settings[<?php echo e($setting->key); ?>][value]" rows="4" class="w-full px-3 py-2 border rounded"><?php echo e($setting->value); ?></textarea>
                                    <input type="hidden" name="settings[<?php echo e($setting->key); ?>][key]" value="<?php echo e($setting->key); ?>">
                                    <?php if($setting->description): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                                    <?php endif; ?>
                                <?php elseif($setting->type === 'number'): ?>
                                    <label class="block text-sm font-medium mb-2"><?php echo e($setting->label ?? $setting->key); ?></label>
                                    <input type="number" name="settings[<?php echo e($setting->key); ?>][value]" value="<?php echo e($setting->value); ?>" class="w-full px-3 py-2 border rounded">
                                    <input type="hidden" name="settings[<?php echo e($setting->key); ?>][key]" value="<?php echo e($setting->key); ?>">
                                    <?php if($setting->description): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                                    <?php endif; ?>
                                <?php elseif($setting->type === 'boolean'): ?>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="text-sm font-medium"><?php echo e($setting->label ?? $setting->key); ?></label>
                                            <?php if($setting->description): ?>
                                                <p class="text-xs text-gray-500"><?php echo e($setting->description); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <input type="checkbox" name="settings[<?php echo e($setting->key); ?>][value]" value="1" <?php echo e($setting->value == '1' ? 'checked' : ''); ?>>
                                    </div>
                                    <input type="hidden" name="settings[<?php echo e($setting->key); ?>][key]" value="<?php echo e($setting->key); ?>">
                                <?php else: ?>
                                    <label class="block text-sm font-medium mb-2"><?php echo e($setting->label ?? $setting->key); ?></label>
                                    <input type="text" name="settings[<?php echo e($setting->key); ?>][value]" value="<?php echo e($setting->value); ?>" class="w-full px-3 py-2 border rounded">
                                    <input type="hidden" name="settings[<?php echo e($setting->key); ?>][key]" value="<?php echo e($setting->key); ?>">
                                    <?php if($setting->description): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($setting->description); ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6 sticky bottom-4">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        <span id="modifiedCount">0</span> settings modified
                    </div>
                    <div class="flex gap-4">
                        <button type="button" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition" onclick="checkAllImageSync()">
                            <i class="fas fa-check-circle mr-2"></i>Check Sync Status
                        </button>
                        <button type="button" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition" onclick="resetToDefaults()">
                            <i class="fas fa-undo mr-2"></i>Reset to Defaults
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-save mr-2"></i>Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function syncAllImages() {
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Syncing...';
    
    const syncUrl = '<?php echo e(route('admin.settings.sync.images')); ?>';
    fetch(syncUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', `Image sync completed! ${data.summary.successful}/${data.summary.total} images synchronized successfully.`);
            updateAllSyncStatuses();
            loadOptimizationSuggestions();
        } else {
            showAlert('error', data.message || 'Image sync failed');
        }
    })
    .catch(error => {
        showAlert('error', 'Network error during sync');
        console.error('Sync error:', error);
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function checkAllImageSync() {
    const imageKeys = <?php echo json_encode(\App\Models\GeneralSetting::where('type', 'image')->pluck('key')->toArray(), 512) ?>;
    
    imageKeys.forEach(key => {
        checkImageSync(key);
        loadOptimizationSuggestions(key);
    });
}

function checkImageSync(key) {
    const statusElement = document.getElementById(`sync-status-${key}`);
    if (!statusElement) return;
    
    statusElement.className = 'image-sync-status';
    statusElement.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i><span>Checking...</span>';
    
    const optimizationBaseUrl = '<?php echo e(route('admin.settings.image.optimization')); ?>';
    fetch(`${optimizationBaseUrl}?key=${key}`)
        .then(response => response.json())
        .then(data => {
            if (data.suggestions && data.suggestions.length > 0) {
                statusElement.className = 'image-sync-status warning';
                statusElement.innerHTML = '<i class="fas fa-exclamation-triangle"></i><span>Needs Optimization</span>';
            } else {
                statusElement.className = 'image-sync-status success';
                statusElement.innerHTML = '<i class="fas fa-check-circle"></i><span>Synced</span>';
            }
        })
        .catch(error => {
            statusElement.className = 'image-sync-status error';
            statusElement.innerHTML = '<i class="fas fa-times-circle"></i><span>Error</span>';
        });
}

function loadOptimizationSuggestions(key = null) {
    const imageKeys = key ? [key] : <?php echo json_encode(\App\Models\GeneralSetting::where('type', 'image')->pluck('key')->toArray(), 512) ?>;
    const optBaseUrl = '<?php echo e(route('admin.settings.image.optimization')); ?>';
    
    imageKeys.forEach(imageKey => {
        fetch(`${optBaseUrl}?key=${imageKey}`)
            .then(response => response.json())
            .then(data => {
                const suggestionsElement = document.getElementById(`optimization-${imageKey}`);
                if (suggestionsElement && data.suggestions && data.suggestions.length > 0) {
                    suggestionsElement.innerHTML = `
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-2">
                            <strong>Optimization Suggestions:</strong>
                            <ul class="list-disc list-inside mt-1">
                                ${data.suggestions.map(s => `<li>${s}</li>`).join('')}
                            </ul>
                        </div>
                    `;
                } else if (suggestionsElement) {
                    suggestionsElement.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Failed to load optimization suggestions:', error);
            });
    });
}

function updateAllSyncStatuses() {
    const imageKeys = <?php echo json_encode(\App\Models\GeneralSetting::where('type', 'image')->pluck('key')->toArray(), 512) ?>;
    imageKeys.forEach(key => checkImageSync(key));
}

function previewImage(input, key) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const existingPreview = input.parentElement.querySelector('.image-preview-container');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            const preview = document.createElement('div');
            preview.className = 'image-preview-container';
            preview.innerHTML = `<img src="${e.target.result}" class="rounded border bg-gray-100" alt="Preview">`;
            input.parentElement.insertBefore(preview, input);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Search functionality
document.getElementById('settingSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const settingGroups = document.querySelectorAll('.bg-white.rounded-lg.shadow-md');

    settingGroups.forEach(group => {
        const settings = group.querySelectorAll('.border-b.pb-6');
        let hasVisibleSettings = false;

        settings.forEach(setting => {
            const label = setting.querySelector('label')?.textContent.toLowerCase() || '';
            const description = setting.querySelector('.text-xs')?.textContent.toLowerCase() || '';
            const key = setting.querySelector('input[type="hidden"]')?.value.toLowerCase() || '';

            if (label.includes(searchTerm) || description.includes(searchTerm) || key.includes(searchTerm)) {
                setting.style.display = 'block';
                hasVisibleSettings = true;
            } else {
                setting.style.display = 'none';
            }
        });

        // Show/hide entire group based on whether it has visible settings
        if (hasVisibleSettings || searchTerm === '') {
            group.style.display = 'block';
        } else {
            group.style.display = 'none';
        }
    });
});

// Expand/Collapse sections
function expandAllSections() {
    const settingsContainers = document.querySelectorAll('.p-6.space-y-6');
    settingsContainers.forEach(container => {
        container.style.display = 'block';
    });
}

function collapseAllSections() {
    const settingsContainers = document.querySelectorAll('.p-6.space-y-6');
    settingsContainers.forEach(container => {
        container.style.display = 'none';
    });
}

// Track modified settings
let originalValues = {};
document.addEventListener('DOMContentLoaded', function() {
    // Store original values
    const inputs = document.querySelectorAll('#settingsForm input, #settingsForm textarea, #settingsForm select');
    inputs.forEach(input => {
        originalValues[input.name] = input.value;
        input.addEventListener('change', updateModifiedCount);
        input.addEventListener('input', updateModifiedCount);
    });
});

function updateModifiedCount() {
    let modifiedCount = 0;
    const inputs = document.querySelectorAll('#settingsForm input[type="text"], #settingsForm input[type="number"], #settingsForm textarea, #settingsForm select');

    inputs.forEach(input => {
        if (input.value !== originalValues[input.name]) {
            modifiedCount++;
        }
    });

    // Check file inputs
    const fileInputs = document.querySelectorAll('#settingsForm input[type="file"]');
    fileInputs.forEach(input => {
        if (input.files.length > 0) {
            modifiedCount++;
        }
    });

    document.getElementById('modifiedCount').textContent = modifiedCount;
}

// Reset to defaults confirmation
function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to their default values? This action cannot be undone.')) {
        // This would typically call an API endpoint to reset settings
        showAlert('info', 'Reset functionality would be implemented on the server side.');
    }
}

// Form submission with validation
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    // Validate JSON fields
    const jsonFields = document.querySelectorAll('textarea[name*="[value]"]');
    let hasError = false;

    jsonFields.forEach(field => {
        const settingKey = field.name.match(/settings\[(.+?)\]/)?.[1];
        if (settingKey && (settingKey.includes('payment') || settingKey.includes('language') || settingKey.includes('gateway'))) {
            try {
                JSON.parse(field.value);
            } catch (error) {
                e.preventDefault();
                hasError = true;
                field.classList.add('border-red-500');
                showAlert('error', `Invalid JSON format in ${settingKey}. Please check your input.`);
            }
        }
    });

    if (!hasError) {
        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;
    }
});

// Initialize sync status on page load
document.addEventListener('DOMContentLoaded', function() {
    checkAllImageSync();

    // Make section headers clickable to toggle visibility
    const sectionHeaders = document.querySelectorAll('.bg-white.rounded-lg.shadow-md > .p-6.border-b');
    sectionHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function(e) {
            // Don't toggle if clicking on buttons
            if (e.target.tagName === 'BUTTON' || e.target.closest('button')) return;

            const content = this.nextElementSibling;
            if (content) {
                content.style.display = content.style.display === 'none' ? 'block' : 'none';
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panchigallery.com\resources\views\admin\settings\general.blade.php ENDPATH**/ ?>