<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionManagementController extends ApiController
{
    use ApiStandardizationTrait;

    /**
     * Display a listing of transactions.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with(['buyer:id,name', 'seller:id,name', 'artwork:id,title']);

        if ($request->filled('search')) {
            $query = $this->applySearch($query, $request, ['transaction_id']);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('buyer_id')) {
            $query->where('buyer_id', $request->buyer_id);
        }

        if ($request->filled('seller_id')) {
            $query->where('seller_id', $request->seller_id);
        }

        $query = $this->applyDateRange($query, $request, 'created_at');
        $query = $this->applySorting($query, $request, ['created_at', 'amount']);
        $transactions = $this->applyPagination($query, $request);

        return $this->paginatedSuccess($transactions);
    }

    /**
     * Display the specified transaction.
     */
    public function show(int $id): JsonResponse
    {
        $transaction = Transaction::with(['buyer', 'seller', 'artwork'])->find($id);

        if (!$transaction) {
            return $this->notFound('Transaction not found');
        }

        return $this->success($transaction);
    }

    /**
     * Update the specified transaction.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return $this->notFound('Transaction not found');
        }

        $validator = Validator::make($request->all(), [
            'status' => ['nullable', 'in:pending,completed,failed'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $transaction->update($request->all());

        return $this->success($transaction, 'Transaction updated successfully');
    }

    /**
     * Complete a transaction.
     */
    public function complete(int $id): JsonResponse
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return $this->notFound('Transaction not found');
        }

        if ($transaction->isCompleted()) {
            return $this->error('Transaction is already completed', 400);
        }

        $transaction->complete();

        return $this->success($transaction, 'Transaction completed successfully');
    }

    /**
     * Get transaction statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Transaction::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_transactions' => (clone $query)->count(),
            'pending_transactions' => (clone $query)->where('status', 'pending')->count(),
            'completed_transactions' => (clone $query)->where('status', 'completed')->count(),
            'failed_transactions' => (clone $query)->where('status', 'failed')->count(),
            'primary_sales' => (clone $query)->where('type', 'primary_sale')->count(),
            'resales' => (clone $query)->where('type', 'resale')->count(),
            'total_amount' => (float) (clone $query)->where('status', 'completed')->sum('amount'),
            'total_platform_fees' => (float) (clone $query)->where('status', 'completed')->sum('platform_fee'),
            'total_seller_earnings' => (float) (clone $query)->where('status', 'completed')->sum('seller_earnings'),
        ];

        return $this->success($stats);
    }
}
