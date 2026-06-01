<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Commission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommissionManagementController extends ApiController
{
    /**
     * Display a listing of commissions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Commission::with(['transaction', 'order', 'artist:id,name,email']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('artist_id')) {
            $query->where('artist_id', $request->artist_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $commissions = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($commissions);
    }

    /**
     * Display the specified commission.
     */
    public function show(int $id): JsonResponse
    {
        $commission = Commission::with(['transaction', 'order', 'artist'])->find($id);

        if (!$commission) {
            return $this->notFound('Commission not found');
        }

        return $this->success($commission);
    }

    /**
     * Update the specified commission.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $commission = Commission::find($id);

        if (!$commission) {
            return $this->notFound('Commission not found');
        }

        $validator = Validator::make($request->all(), [
            'platform_fee' => ['sometimes', 'required', 'numeric', 'min:0'],
            'artist_earnings' => ['sometimes', 'required', 'numeric', 'min:0'],
            'platform_fee_percentage' => ['sometimes', 'required', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $commission->update($request->all());

        return $this->success($commission, 'Commission updated successfully');
    }

    /**
     * Mark commission as paid.
     */
    public function markAsPaid(Request $request, int $id): JsonResponse
    {
        $commission = Commission::find($id);

        if (!$commission) {
            return $this->notFound('Commission not found');
        }

        if ($commission->status === 'paid') {
            return $this->error('Commission is already paid', 400);
        }

        $validator = Validator::make($request->all(), [
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $commission->markAsPaid();
        
        if ($request->filled('notes')) {
            $commission->update(['notes' => $request->notes]);
        }

        return $this->success($commission, 'Commission marked as paid successfully');
    }

    /**
     * Get commission statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Commission::query();

        if ($request->filled('artist_id')) {
            $query->where('artist_id', $request->artist_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_commissions' => $query->count(),
            'pending_commissions' => (clone $query)->pending()->count(),
            'paid_commissions' => (clone $query)->paid()->count(),
            'total_platform_fees' => (float) (clone $query)->sum('platform_fee'),
            'total_artist_earnings' => (float) (clone $query)->sum('artist_earnings'),
            'pending_platform_fees' => (float) (clone $query)->pending()->sum('platform_fee'),
            'pending_artist_earnings' => (float) (clone $query)->pending()->sum('artist_earnings'),
        ];

        return $this->success($stats);
    }

    /**
     * Bulk mark commissions as paid.
     */
    public function bulkMarkAsPaid(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'commission_ids' => ['required', 'array'],
            'commission_ids.*' => ['exists:commissions,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $commissions = Commission::whereIn('id', $request->commission_ids)
            ->where('status', 'pending')
            ->get();

        foreach ($commissions as $commission) {
            $commission->markAsPaid();
            if ($request->filled('notes')) {
                $commission->update(['notes' => $request->notes]);
            }
        }

        return $this->success(null, 'Commissions marked as paid successfully');
    }
}
