<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\GeneralSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SettingsController extends ApiController
{
    /**
     * Get all general settings.
     */
    public function index(): JsonResponse
    {
        $settings = GeneralSetting::all()->groupBy('group');
        return $this->success($settings);
    }

    /**
     * Update settings.
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string', 'exists:general_settings,key'],
            'settings.*.value' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        foreach ($request->settings as $settingData) {
            GeneralSetting::where('key', $settingData['key'])->update([
                'value' => $settingData['value']
            ]);
        }

        // Clear cache
        Cache::forget('general_settings');

        return $this->success(null, 'Settings updated successfully');
    }

    /**
     * Get platform statistics and health.
     */
    public function health(): JsonResponse
    {
        $healthService = app(\App\Services\SystemHealthService::class);
        return $this->success($healthService->getStatus());
    }
}
