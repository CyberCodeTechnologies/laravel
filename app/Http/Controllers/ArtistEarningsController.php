<?php

namespace App\Http\Controllers;

use App\Services\CommissionService;
use App\Models\Commission;
use App\Models\Payout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtistEarningsController extends Controller
{
    protected CommissionService $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Show artist earnings dashboard.
     */
    public function dashboard()
    {
        $artistId = Auth::id();
        
        $stats = $this->commissionService->getArtistEarningsStats($artistId);
        $pendingCommissions = $this->commissionService->getPendingCommissionsForArtist($artistId);
        $payoutHistory = Payout::forArtist($artistId)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('artist.earnings.dashboard', compact(
            'stats',
            'pendingCommissions',
            'payoutHistory'
        ));
    }

    /**
     * Show commission history.
     */
    public function commissions()
    {
        $artistId = Auth::id();
        
        $commissions = Commission::forArtist($artistId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('artist.earnings.commissions', compact('commissions'));
    }

    /**
     * Show payout history.
     */
    public function payouts()
    {
        $artistId = Auth::id();
        
        $payouts = Payout::forArtist($artistId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('artist.earnings.payouts', compact('payouts'));
    }

    /**
     * Request a payout.
     */
    public function requestPayout(Request $request)
    {
        $artistId = Auth::id();
        
        $request->validate([
            'method' => 'required|in:bank_transfer,paypal,stripe',
        ]);

        // Check if there's already a pending payout
        $existingPayout = Payout::forArtist($artistId)
            ->whereIn('status', ['pending', 'processing'])
            ->first();

        if ($existingPayout) {
            return redirect()->back()->with('error', 'You already have a pending payout request.');
        }

        // Check if there are pending commissions
        $pendingEarnings = $this->commissionService->getPendingEarningsForArtist($artistId);

        if ($pendingEarnings <= 0) {
            return redirect()->back()->with('error', 'No pending earnings available for payout.');
        }

        // Create payout request
        $payout = $this->commissionService->requestPayout($artistId, $request->method);

        if ($payout) {
            return redirect()->back()->with('success', 'Payout request submitted successfully. Amount: $' . number_format($payout->amount, 2));
        }

        return redirect()->back()->with('error', 'Failed to submit payout request.');
    }

    /**
     * Download tax report.
     */
    public function downloadTaxReport(Request $request)
    {
        $artistId = Auth::id();
        
        $request->validate([
            'year' => 'required|integer|min:2020|max:' . now()->year,
        ]);

        $year = $request->year;

        $commissions = Commission::forArtist($artistId)
            ->paid()
            ->whereYear('paid_at', $year)
            ->get();

        $totalEarnings = $commissions->sum('artist_earnings');
        $totalFees = $commissions->sum('platform_fee');

        // Generate CSV
        $filename = "tax_report_{$year}_" . Auth::user()->name . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($commissions, $totalEarnings, $totalFees, $year) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Tax Report', $year]);
            fputcsv($file, []);
            fputcsv($file, ['Total Earnings', '$' . number_format($totalEarnings, 2)]);
            fputcsv($file, ['Total Platform Fees', '$' . number_format($totalFees, 2)]);
            fputcsv($file, []);
            fputcsv($file, ['Date', 'Sale Amount', 'Platform Fee', 'Your Earnings', 'Transaction ID']);

            foreach ($commissions as $commission) {
                fputcsv($file, [
                    $commission->paid_at->format('Y-m-d'),
                    $commission->sale_amount,
                    $commission->platform_fee,
                    $commission->artist_earnings,
                    $commission->transaction_id,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
