<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Shipment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShipmentManagementController extends ApiController
{
    /**
     * Display a listing of shipments.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Shipment::with(['order.user', 'order.items.artwork']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('carrier')) {
            $query->where('carrier', $request->carrier);
        }

        if ($request->filled('tracking_number')) {
            $query->where('tracking_number', 'like', '%' . $request->tracking_number . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('shipped_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('shipped_at', '<=', $request->date_to);
        }

        $shipments = $query->latest()->paginate($request->get('per_page', 15));

        return $this->success($shipments);
    }

    /**
     * Display the specified shipment.
     */
    public function show(int $id): JsonResponse
    {
        $shipment = Shipment::with(['order.user', 'order.items.artwork'])->find($id);

        if (!$shipment) {
            return $this->notFound('Shipment not found');
        }

        return $this->success($shipment);
    }

    /**
     * Update the specified shipment.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return $this->notFound('Shipment not found');
        }

        $validator = Validator::make($request->all(), [
            'tracking_number' => ['nullable', 'string', 'max:255'],
            'carrier' => ['nullable', 'string', 'max:100'],
            'service' => ['nullable', 'string', 'max:100'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'in:USD,MMK'],
            'status' => ['nullable', 'in:pending,picked_up,in_transit,out_for_delivery,delivered'],
            'estimated_delivery' => ['nullable', 'date'],
            'shipping_address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'label_url' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $shipment->update($request->all());

        return $this->success($shipment, 'Shipment updated successfully');
    }

    /**
     * Update shipment status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return $this->notFound('Shipment not found');
        }

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'in:pending,picked_up,in_transit,out_for_delivery,delivered'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $shipment->update(['status' => $request->status]);

        // Auto-update timestamps based on status
        if ($request->status === 'picked_up' && !$shipment->shipped_at) {
            $shipment->update(['shipped_at' => now()]);
        }

        if ($request->status === 'delivered' && !$shipment->delivered_at) {
            $shipment->markAsDelivered();
        }

        return $this->success($shipment, 'Shipment status updated successfully');
    }

    /**
     * Add tracking event to shipment.
     */
    public function addTrackingEvent(Request $request, int $id): JsonResponse
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return $this->notFound('Shipment not found');
        }

        $validator = Validator::make($request->all(), [
            'event' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $shipment->addTrackingEvent(
            $request->event,
            $request->location,
            $request->description
        );

        return $this->success($shipment, 'Tracking event added successfully');
    }

    /**
     * Track shipment (external API integration).
     */
    public function track(int $id): JsonResponse
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return $this->notFound('Shipment not found');
        }

        if (!$shipment->tracking_number) {
            return $this->error('No tracking number available', 400);
        }

        // This would integrate with carrier APIs
        // For now, return current tracking history
        $trackingHistory = $shipment->tracking_history ?? [];

        return $this->success([
            'tracking_number' => $shipment->tracking_number,
            'carrier' => $shipment->carrier,
            'status' => $shipment->status,
            'tracking_url' => $shipment->getTrackingUrl(),
            'tracking_history' => $trackingHistory,
        ]);
    }

    /**
     * Get shipment statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Shipment::query();

        if ($request->filled('date_from')) {
            $query->whereDate('shipped_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('shipped_at', '<=', $request->date_to);
        }

        $stats = [
            'total_shipments' => $query->count(),
            'pending_shipments' => (clone $query)->pending()->count(),
            'in_transit_shipments' => (clone $query)->inTransit()->count(),
            'delivered_shipments' => (clone $query)->delivered()->count(),
            'total_shipping_cost' => (float) (clone $query)->sum('shipping_cost'),
        ];

        return $this->success($stats);
    }
}
