<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\PaymentProof;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentProofManagementController extends ApiController
{
    /**
     * Display a listing of payment proofs.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PaymentProof::with(['order.user', 'paymentMethod', 'verifier:id,name']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $paymentProofs = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($paymentProofs);
    }

    /**
     * Display the specified payment proof.
     */
    public function show(int $id): JsonResponse
    {
        $paymentProof = PaymentProof::with(['order.user', 'order.items.artwork', 'paymentMethod', 'verifier'])->find($id);

        if (!$paymentProof) {
            return $this->notFound('Payment proof not found');
        }

        return $this->success($paymentProof);
    }

    /**
     * Update the specified payment proof.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $paymentProof = PaymentProof::find($id);

        if (!$paymentProof) {
            return $this->notFound('Payment proof not found');
        }

        $validator = Validator::make($request->all(), [
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $paymentProof->update($request->all());

        return $this->success($paymentProof, 'Payment proof updated successfully');
    }

    /**
     * Approve a payment proof.
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        $paymentProof = PaymentProof::find($id);

        if (!$paymentProof) {
            return $this->notFound('Payment proof not found');
        }

        if (!$paymentProof->isPending()) {
            return $this->error('Payment proof is not in pending status', 400);
        }

        $validator = Validator::make($request->all(), [
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $paymentProof->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Update order status
        if ($paymentProof->order) {
            $paymentProof->order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);
        }

        return $this->success($paymentProof, 'Payment proof approved successfully');
    }

    /**
     * Reject a payment proof.
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        $paymentProof = PaymentProof::find($id);

        if (!$paymentProof) {
            return $this->notFound('Payment proof not found');
        }

        if (!$paymentProof->isPending()) {
            return $this->error('Payment proof is not in pending status', 400);
        }

        $validator = Validator::make($request->all(), [
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $paymentProof->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Update order status
        if ($paymentProof->order) {
            $paymentProof->order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);
        }

        return $this->success($paymentProof, 'Payment proof rejected successfully');
    }

    /**
     * Get payment proof statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = PaymentProof::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_payment_proofs' => $query->count(),
            'pending_payment_proofs' => (clone $query)->pending()->count(),
            'approved_payment_proofs' => (clone $query)->approved()->count(),
            'rejected_payment_proofs' => (clone $query)->rejected()->count(),
        ];

        return $this->success($stats);
    }

    /**
     * Bulk approve payment proofs.
     */
    public function bulkApprove(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_proof_ids' => ['required', 'array'],
            'payment_proof_ids.*' => ['exists:payment_proofs,id'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $paymentProofs = PaymentProof::whereIn('id', $request->payment_proof_ids)
            ->where('status', 'pending')
            ->get();

        foreach ($paymentProofs as $paymentProof) {
            $paymentProof->update([
                'status' => 'approved',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'admin_notes' => $request->admin_notes,
            ]);

            if ($paymentProof->order) {
                $paymentProof->order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                ]);
            }
        }

        return $this->success(null, 'Payment proofs approved successfully');
    }

    /**
     * Bulk reject payment proofs.
     */
    public function bulkReject(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_proof_ids' => ['required', 'array'],
            'payment_proof_ids.*' => ['exists:payment_proofs,id'],
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $paymentProofs = PaymentProof::whereIn('id', $request->payment_proof_ids)
            ->where('status', 'pending')
            ->get();

        foreach ($paymentProofs as $paymentProof) {
            $paymentProof->update([
                'status' => 'rejected',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'admin_notes' => $request->admin_notes,
            ]);

            if ($paymentProof->order) {
                $paymentProof->order->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);
            }
        }

        return $this->success(null, 'Payment proofs rejected successfully');
    }
}
