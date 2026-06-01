<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderManagementController extends ApiController
{
    use ApiStandardizationTrait;

    /**
     * Display a listing of orders.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user', 'items.artwork', 'shipment']);

        if ($request->filled('search')) {
            $query = $this->applySearch($query, $request, ['order_number', 'customer_name', 'customer_email']);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('order_type')) {
            $query->where('order_type', $request->order_type);
        }

        $query = $this->applyDateRange($query, $request, 'created_at');
        $query = $this->applySorting($query, $request, ['created_at', 'total_amount', 'order_number']);
        $orders = $this->applyPagination($query, $request);

        return $this->paginatedSuccess($orders);
    }

    /**
     * Display the specified order.
     */
    public function show(int $id): JsonResponse
    {
        $order = Order::with(['user', 'items.artwork.artist', 'shipment', 'paymentProofs'])->find($id);

        if (!$order) {
            return $this->notFound('Order not found');
        }

        return $this->success($order);
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->notFound('Order not found');
        }

        $validator = Validator::make($request->all(), [
            'status' => ['nullable', 'in:pending,paid,processing,shipped,delivered,cancelled,refunded,on_hold,partially_refunded'],
            'payment_status' => ['nullable', 'in:pending,paid,failed,refunded'],
            'shipping_address' => ['nullable', 'string'],
            'order_notes' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $order->update($request->all());

        return $this->success($order, 'Order updated successfully');
    }

    /**
     * Cancel an order.
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->notFound('Order not found');
        }

        if (!$order->canBeCancelled()) {
            return $this->error('Order cannot be cancelled', 400);
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $order->update([
            'status' => 'cancelled',
            'order_notes' => $request->cancellation_reason,
        ]);

        return $this->success($order, 'Order cancelled successfully');
    }

    /**
     * Process a refund for an order.
     */
    public function refund(Request $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->notFound('Order not found');
        }

        if (!$order->canBeRefunded()) {
            return $this->error('Order cannot be refunded', 400);
        }

        $validator = Validator::make($request->all(), [
            'refund_amount' => ['required', 'numeric', 'min:0', 'max:' . $order->total_amount],
            'refund_reason' => ['required', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $order->initiateRefund($request->refund_amount);
        $order->update(['order_notes' => $request->refund_reason]);

        return $this->success($order, 'Refund initiated successfully');
    }

    /**
     * Put order on hold.
     */
    public function putOnHold(Request $request, int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->notFound('Order not found');
        }

        $validator = Validator::make($request->all(), [
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $order->putOnHold($request->reason);

        return $this->success($order, 'Order put on hold successfully');
    }

    /**
     * Release order from hold.
     */
    public function releaseFromHold(int $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->notFound('Order not found');
        }

        $order->releaseFromHold();

        return $this->success($order, 'Order released from hold successfully');
    }

    /**
     * Get order statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Order::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_orders' => (clone $query)->count(),
            'pending_orders' => (clone $query)->where('status', 'pending')->count(),
            'paid_orders' => (clone $query)->where('payment_status', 'paid')->count(),
            'processing_orders' => (clone $query)->where('status', 'processing')->count(),
            'shipped_orders' => (clone $query)->where('status', 'shipped')->count(),
            'delivered_orders' => (clone $query)->where('status', 'delivered')->count(),
            'cancelled_orders' => (clone $query)->where('status', 'cancelled')->count(),
            'refunded_orders' => (clone $query)->where('status', 'refunded')->count(),
            'total_revenue' => (float) (clone $query)->where('payment_status', 'paid')->sum('total_amount'),
            'average_order_value' => (float) (clone $query)->where('payment_status', 'paid')->avg('total_amount') ?? 0,
        ];

        return $this->success($stats);
    }
}
