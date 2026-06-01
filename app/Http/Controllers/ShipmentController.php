<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    /**
     * Show shipment details for order.
     */
    public function show(Order $order)
    {
        $shipment = $order->shipment;
        return view('shipments.show', compact('order', 'shipment'));
    }

    /**
     * Create shipment for order (admin/artist).
     */
    public function create(Request $request, Order $order)
    {
        $request->validate([
            'carrier' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'service' => 'nullable|string|max:255',
            'shipping_cost' => 'nullable|numeric|min:0',
            'estimated_delivery' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $shipment = Shipment::create([
            'order_id' => $order->id,
            'carrier' => $request->carrier,
            'tracking_number' => $request->tracking_number,
            'service' => $request->service,
            'shipping_cost' => $request->shipping_cost,
            'estimated_delivery' => $request->estimated_delivery,
            'notes' => $request->notes,
            'shipping_address' => $order->shipping_address ?? $order->address,
            'status' => 'label_created',
        ]);

        // Update order status
        $order->update(['status' => 'shipped']);

        return redirect()->back()->with('success', 'Shipment created successfully.');
    }

    /**
     * Update shipment tracking.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'status' => 'required|in:pending,label_created,picked_up,in_transit,out_for_delivery,delivered,exception',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        $shipment->update(['status' => $request->status]);

        // Add tracking event
        $shipment->addTrackingEvent(
            'Status updated to: ' . ucfirst(str_replace('_', ' ', $request->status)),
            null,
            $request->notes
        );

        // If delivered, mark order as delivered
        if ($request->status === 'delivered') {
            $shipment->markAsDelivered();
        }

        return redirect()->back()->with('success', 'Shipment updated successfully.');
    }

    /**
     * Add tracking event.
     */
    public function addEvent(Request $request, Shipment $shipment)
    {
        $request->validate([
            'event' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $shipment->addTrackingEvent(
            $request->event,
            $request->location,
            $request->description
        );

        return redirect()->back()->with('success', 'Tracking event added.');
    }

    /**
     * Track shipment (public).
     */
    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        $shipment = Shipment::where('tracking_number', $request->tracking_number)->first();

        if (!$shipment) {
            return redirect()->back()->with('error', 'Tracking number not found.');
        }

        return view('shipments.track', compact('shipment'));
    }
}
