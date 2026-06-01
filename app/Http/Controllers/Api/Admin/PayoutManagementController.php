<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Payout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayoutManagementController extends ApiController
{
    /**
     * Display a listing of payouts.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Payout::with(['artist:id,name,email', 'processor:id,name']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('artist_id')) {
            $query->where('artist_id', $request->artist_id);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('requested_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('requested_at', '<=', $request->date_to);
        }

        $payouts = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($payouts);
    }

    /**
     * Display the specified payout.
     */
    public function show(int $id): JsonResponse
    {
        $payout = Payout::with(['artist', 'processor', 'commissions'])->find($id);

        if (!$payout) {
            return $this->notFound('Payout not found');
        }

        return $this->success($payout);
    }

    /**
     * Update the specified payout.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $payout = Payout::find($id);

        if (!$payout) {
            return $this->notFound('Payout not found');
        }

        $validator = Validator::make($request->all(), [
            'method' => ['nullable', 'string', 'max:50'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $payout->update($request->all());

        return $this->success($payout, 'Payout updated successfully');
    }

    /**
     * Process a payout.
     */
    public function process(Request $request, int $id): JsonResponse
    {
        $payout = Payout::find($id);

        if (!$payout) {
            return $this->notFound('Payout not found');
        }

        if (!$payout->isPending()) {
            return $this->error('Payout is not in pending status', 400);
        }

        $validator = Validator::make($request->all(), [
            'payment_reference' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $payout->markAsProcessing();
        $payout->update([
            'payment_reference' => $request->payment_reference,
            'processed_by' => auth()->id(),
        ]);

        return $this->success($payout, 'Payout processed successfully');
    }

    /**
     * Complete a payout.
     */
    public function complete(Request $request, int $id): JsonResponse
    {
        $payout = Payout::find($id);

        if (!$payout) {
            return $this->notFound('Payout not found');
        }

        if (!$payout->isProcessing()) {
            return $this->error('Payout is not in processing status', 400);
        }

        $validator = Validator::make($request->all(), [
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $payout->markAsCompleted($request->payment_reference);
        $payout->update(['processed_by' => auth()->id()]);
        
        if ($request->filled('notes')) {
            $payout->update(['notes' => $request->notes]);
        }

        return $this->success($payout, 'Payout completed successfully');
    }

    /**
     * Reject a payout.
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $payout = Payout::find($id);

        if (!$payout) {
            return $this->notFound('Payout not found');
        }

        if ($payout->isCompleted()) {
            return $this->error('Cannot reject a completed payout', 400);
        }

        $validator = Validator::make($request->all(), [
            'failure_reason' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $payout->markAsFailed($request->failure_reason);
        $payout->update(['processed_by' => auth()->id()]);

        return $this->success($payout, 'Payout rejected successfully');
    }

    /**
     * Get payout statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Payout::query();

        if ($request->filled('artist_id')) {
            $query->where('artist_id', $request->artist_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('requested_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('requested_at', '<=', $request->date_to);
        }

        $stats = [
            'total_payouts' => $query->count(),
            'pending_payouts' => (clone $query)->pending()->count(),
            'processing_payouts' => (clone $query)->processing()->count(),
            'completed_payouts' => (clone $query)->completed()->count(),
            'failed_payouts' => (clone $query)->where('status', 'failed')->count(),
            'total_amount' => (float) (clone $query)->sum('amount'),
            'pending_amount' => (float) (clone $query)->pending()->sum('amount'),
            'processing_amount' => (float) (clone $query)->processing()->sum('amount'),
            'completed_amount' => (float) (clone $query)->completed()->sum('amount'),
        ];

        return $this->success($stats);
    }

    /**
     * Bulk process payouts.
     */
    public function bulkProcess(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payout_ids' => ['required', 'array'],
            'payout_ids.*' => ['exists:payouts,id'],
            'payment_reference' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $payouts = Payout::whereIn('id', $request->payout_ids)
            ->where('status', 'pending')
            ->get();

        foreach ($payouts as $payout) {
            $payout->markAsProcessing();
            $payout->update([
                'payment_reference' => $request->payment_reference,
                'processed_by' => auth()->id(),
            ]);
            if ($request->filled('notes')) {
                $payout->update(['notes' => $request->notes]);
            }
        }

        return $this->success(null, 'Payouts processed successfully');
    }
}
