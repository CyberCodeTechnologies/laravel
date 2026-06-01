<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\PromoCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromoCodeManagementController extends ApiController
{
    /**
     * Display a listing of promo codes.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PromoCode::query();

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $promoCodes = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($promoCodes);
    }

    /**
     * Store a newly created promo code.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:50', 'unique:promo_codes'],
            'description' => ['nullable', 'string', 'max:500'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();
        $data['code'] = strtoupper($request->code);
        $data['usage_count'] = 0;

        $promoCode = PromoCode::create($data);

        return $this->success($promoCode, 'Promo code created successfully', 201);
    }

    /**
     * Display the specified promo code.
     */
    public function show(int $id): JsonResponse
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return $this->notFound('Promo code not found');
        }

        return $this->success($promoCode);
    }

    /**
     * Update the specified promo code.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return $this->notFound('Promo code not found');
        }

        $validator = Validator::make($request->all(), [
            'code' => ['sometimes', 'required', 'string', 'max:50', 'unique:promo_codes,code,' . $id],
            'description' => ['nullable', 'string', 'max:500'],
            'type' => ['sometimes', 'required', 'in:percentage,fixed'],
            'value' => ['sometimes', 'required', 'numeric', 'min:0'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $data = $request->all();

        if ($request->has('code')) {
            $data['code'] = strtoupper($request->code);
        }

        $promoCode->update($data);

        return $this->success($promoCode, 'Promo code updated successfully');
    }

    /**
     * Remove the specified promo code.
     */
    public function destroy(int $id): JsonResponse
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return $this->notFound('Promo code not found');
        }

        $promoCode->delete();

        return $this->success(null, 'Promo code deleted successfully');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(int $id): JsonResponse
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return $this->notFound('Promo code not found');
        }

        $promoCode->update(['is_active' => !$promoCode->is_active]);

        return $this->success($promoCode, 'Active status toggled successfully');
    }

    /**
     * Reset usage count.
     */
    public function resetUsage(int $id): JsonResponse
    {
        $promoCode = PromoCode::find($id);

        if (!$promoCode) {
            return $this->notFound('Promo code not found');
        }

        $promoCode->update(['usage_count' => 0]);

        return $this->success($promoCode, 'Usage count reset successfully');
    }

    /**
     * Get promo code statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_promo_codes' => PromoCode::count(),
            'active_promo_codes' => PromoCode::where('is_active', true)->count(),
            'expired_promo_codes' => PromoCode::where('expires_at', '<', now())->count(),
            'valid_promo_codes' => PromoCode::valid()->count(),
            'total_usage' => PromoCode::sum('usage_count'),
        ];

        return $this->success($stats);
    }
}
