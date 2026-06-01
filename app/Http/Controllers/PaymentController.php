<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentProof;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::active()->get();
        return view('payments.index', compact('paymentMethods'));
    }

    public function processPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        // Update order with payment method
        $order->update(['payment_method_id' => $paymentMethod->id]);

        // Handle different payment types
        if ($paymentMethod->requires_manual_verification) {
            // For manual payments (bank transfer, mobile payments)
            return redirect()->route('payment.manual', ['order' => $order, 'method' => $paymentMethod->code]);
        }

        // For automatic payments (Stripe, PayPal)
        switch ($paymentMethod->type) {
            case 'stripe':
                return redirect()->route('payment.stripe', $order);
            case 'paypal':
                return redirect()->route('payment.paypal', $order);
            default:
                return redirect()->back()->with('error', __('messages.invalid_payment_method'));
        }
    }

    public function showManualPaymentForm(Order $order, $method)
    {
        $paymentMethod = PaymentMethod::where('code', $method)->firstOrFail();
        
        return view('payments.manual', compact('order', 'paymentMethod'));
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        $request->validate([
            'screenshot' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Store screenshot
        $path = $request->file('screenshot')->store('payment_proofs', 'public');

        // Create payment proof record
        PaymentProof::create([
            'order_id' => $order->id,
            'payment_method_id' => $order->payment_method_id,
            'screenshot' => $path,
            'transaction_reference' => $request->transaction_reference,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Update order payment status
        $order->update(['payment_status' => 'pending']);

        // Redirect authenticated users to their orders, guests to checkout success
        if (auth()->check()) {
            return redirect()->route('orders.show', $order)->with('success', __('messages.payment_uploaded'));
        }

        return redirect()->route('checkout.success', ['order_id' => $order->id])
            ->with('success', __('messages.payment_proof_uploaded_success'));
    }

    // Admin Methods
    public function adminVerifications(Request $request)
    {
        $status = $request->input('status', 'pending');
        
        $query = PaymentProof::with(['order', 'paymentMethod', 'verifier']);
        
        if ($status === 'approved') {
            $query->approved();
        } elseif ($status === 'rejected') {
            $query->rejected();
        } else {
            $query->pending();
        }
            
        $proofs = $query->latest()->paginate(20);

        return view('admin.payments.verifications', compact('proofs'));
    }

    public function approvePayment(Request $request, PaymentProof $proof)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $proof->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Update order status
        $proof->order->update([
            'payment_status' => 'completed',
            'status' => 'paid', // Move from pending to paid
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', __('messages.payment_approved'));
    }

    public function rejectPayment(Request $request, PaymentProof $proof)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $proof->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Update order status
        $proof->order->update([
            'payment_status' => 'failed',
        ]);

        return redirect()->back()->with('success', __('messages.payment_rejected'));
    }
}
