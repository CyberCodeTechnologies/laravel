<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Artwork;
use App\Models\Ownership;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions for authenticated user.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Transaction::with(['artwork', 'buyer', 'seller']);
        
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Show transactions where user is buyer or seller
        $transactions = $query->where(function ($q) use ($user) {
            $q->where('buyer_id', $user->id)
              ->orWhere('seller_id', $user->id);
        })->latest()->paginate(15);
        
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        
        $transaction->load(['artwork.artist', 'buyer', 'seller']);
        
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Create a new transaction for artwork purchase.
     */
    public function create(Request $request, Artwork $artwork)
    {
        if (!$artwork->isAvailable()) {
            return back()->with('error', 'Artwork is not available for purchase.');
        }
        
        // Calculate platform fee and seller earnings
        $platformFee = Transaction::calculatePlatformFee($artwork->price);
        $sellerEarnings = Transaction::calculateSellerEarnings($artwork->price, $platformFee);
        
        return view('transactions.create', compact('artwork', 'platformFee', 'sellerEarnings'));
    }

    /**
     * Store a new transaction.
     */
    public function store(Request $request, Artwork $artwork)
    {
        if (!$artwork->isAvailable()) {
            return back()->with('error', 'Artwork is not available for purchase.');
        }
        
        $request->validate([
            'payment_method' => ['required', 'in:stripe,paypal,bank_transfer'],
            'buyer_notes' => ['nullable', 'string', 'max:500'],
        ]);
        
        $buyer = auth()->user();
        $seller = $artwork->artist;
        
        // Check if buyer is trying to purchase their own artwork
        if ($buyer->id === $seller->id) {
            return back()->with('error', 'Cannot purchase your own artwork.');
        }
        
        // Calculate fees
        $platformFee = Transaction::calculatePlatformFee($artwork->price);
        $sellerEarnings = Transaction::calculateSellerEarnings($artwork->price, $platformFee);
        
        // Create transaction
        $transaction = Transaction::create([
            'artwork_id' => $artwork->id,
            'buyer_id' => $buyer->id,
            'seller_id' => $seller->id,
            'transaction_id' => Transaction::generateTransactionId(),
            'amount' => $artwork->price,
            'currency' => 'USD',
            'platform_fee' => $platformFee,
            'seller_earnings' => $sellerEarnings,
            'type' => 'primary_sale',
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);
        
        // Process payment (this would integrate with payment gateways)
        $paymentResult = $this->processPayment($transaction, $request);
        
        if ($paymentResult['success']) {
            $transaction->update([
                'status' => 'completed',
                'payment_id' => $paymentResult['payment_id'],
                'completed_at' => now(),
            ]);
            
            // Complete the transaction (transfers ownership, updates artwork status)
            $transaction->complete();
            
            // Send notifications
            // This would be implemented with a notification system
            
            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Purchase completed successfully!');
        } else {
            $transaction->update([
                'status' => 'failed',
            ]);
            
            return back()->with('error', 'Payment failed: ' . $paymentResult['message']);
        }
    }

    /**
     * Process payment for transaction.
     */
    private function processPayment(Transaction $transaction, Request $request)
    {
        // This would integrate with actual payment gateways (Stripe, PayPal, etc.)
        // For now, we'll simulate the payment process
        
        try {
            // Simulate payment processing
            switch ($request->payment_method) {
                case 'stripe':
                    // Stripe integration would go here
                    return [
                        'success' => true,
                        'payment_id' => 'stripe_' . uniqid(),
                    ];
                    
                case 'paypal':
                    // PayPal integration would go here
                    return [
                        'success' => true,
                        'payment_id' => 'paypal_' . uniqid(),
                    ];
                    
                case 'bank_transfer':
                    // Bank transfer would need manual verification
                    return [
                        'success' => true,
                        'payment_id' => 'bank_' . uniqid(),
                    ];
                    
                default:
                    return [
                        'success' => false,
                        'message' => 'Unsupported payment method.',
                    ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Refund a transaction.
     */
    public function refund(Transaction $transaction)
    {
        $this->authorize('refund', $transaction);
        
        if ($transaction->status !== 'completed') {
            return back()->with('error', 'Cannot refund incomplete transaction.');
        }
        
        // Process refund (this would integrate with payment gateways)
        $refundResult = $this->processRefund($transaction);
        
        if ($refundResult['success']) {
            $transaction->update([
                'status' => 'refunded',
            ]);
            
            // Reverse ownership transfer
            $currentOwnership = $transaction->artwork->ownerships()
                ->where('is_current_owner', true)
                ->first();
            
            if ($currentOwnership) {
                // Transfer back to original seller
                $currentOwnership->transferTo(
                    $transaction->seller,
                    $transaction->amount,
                    'refund'
                );
            }
            
            // Update artwork status back to approved
            $transaction->artwork->update(['status' => 'approved']);
            
            return back()->with('success', 'Refund processed successfully.');
        } else {
            return back()->with('error', 'Refund failed: ' . $refundResult['message']);
        }
    }

    /**
     * Process refund for transaction.
     */
    private function processRefund(Transaction $transaction)
    {
        // This would integrate with actual payment gateways
        // For now, we'll simulate the refund process
        
        try {
            // Simulate refund processing
            return [
                'success' => true,
                'refund_id' => 'refund_' . uniqid(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get transaction statistics.
     */
    public function statistics()
    {
        $user = auth()->user();
        
        $stats = [
            'total_purchases' => $user->purchases()->completed()->count(),
            'total_purchases_amount' => $user->purchases()->completed()->sum('amount'),
            'total_sales' => $user->sales()->completed()->count(),
            'total_sales_amount' => $user->sales()->completed()->sum('seller_earnings'),
            'pending_transactions' => $user->purchases()->pending()->count(),
            'completed_transactions' => $user->purchases()->completed()->count(),
        ];
        
        return response()->json($stats);
    }
}
