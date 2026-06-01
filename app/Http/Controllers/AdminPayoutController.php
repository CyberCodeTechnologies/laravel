<?php

namespace App\Http\Controllers;

use App\Services\CommissionService;
use App\Models\Payout;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPayoutController extends Controller
{
    protected CommissionService $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Show pending payouts.
     */
    public function pending()
    {
        $payouts = Payout::pending()
            ->with('artist')
            ->orderBy('requested_at', 'asc')
            ->paginate(20);

        $stats = [
            'total_pending_amount' => Payout::pending()->sum('amount'),
            'total_pending_count' => Payout::pending()->count(),
            'total_processing_count' => Payout::processing()->count(),
        ];

        return view('admin.payouts.pending', compact('payouts', 'stats'));
    }

    /**
     * Show all payouts.
     */
    public function index(Request $request)
    {
        $query = Payout::with('artist', 'processor');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('artist')) {
            $query->whereHas('artist', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->artist . '%');
            });
        }

        $payouts = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.payouts.index', compact('payouts'));
    }

    /**
     * Show payout details.
     */
    public function show(Payout $payout)
    {
        $payout->load('artist', 'processor');
        $commissions = $payout->commissions();

        return view('admin.payouts.show', compact('payout', 'commissions'));
    }

    /**
     * Process payout.
     */
    public function process(Request $request, Payout $payout)
    {
        $request->validate([
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!$payout->isPending()) {
            return redirect()->back()->with('error', 'This payout cannot be processed.');
        }

        $payout->update(['notes' => $request->notes]);

        $success = $this->commissionService->processPayout(
            $payout,
            Auth::id(),
            $request->payment_reference
        );

        if ($success) {
            return redirect()->route('admin.payouts.pending')
                ->with('success', 'Payout processed successfully.');
        }

        return redirect()->back()->with('error', 'Failed to process payout.');
    }

    /**
     * Reject payout.
     */
    public function reject(Request $request, Payout $payout)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        if (!$payout->isPending()) {
            return redirect()->back()->with('error', 'This payout cannot be rejected.');
        }

        $payout->markAsFailed($request->reason);
        $payout->update(['processed_by' => Auth::id()]);

        return redirect()->route('admin.payouts.pending')
            ->with('success', 'Payout rejected.');
    }

    /**
     * Show platform revenue stats.
     */
    public function revenue()
    {
        $stats = $this->commissionService->getPlatformRevenueStats();
        
        $monthlyRevenue = Commission::selectRaw('SUM(platform_fee) as total, MONTH(created_at) as month, YEAR(created_at) as year')
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.payouts.revenue', compact('stats', 'monthlyRevenue'));
    }

    /**
     * Update platform fee percentage.
     */
    public function updateFeePercentage(Request $request)
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $this->commissionService->setFeePercentage($request->percentage);

        return redirect()->back()->with('success', 'Platform fee percentage updated.');
    }
}
