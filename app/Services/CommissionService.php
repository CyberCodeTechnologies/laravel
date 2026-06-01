<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\Payout;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionService
{
    /**
     * Get platform fee percentage from config.
     */
    public function getFeePercentage(): float
    {
        return config('platform.fee_percentage', 10);
    }

    /**
     * Create commission record from transaction.
     */
    public function createCommissionFromTransaction(Transaction $transaction): Commission
    {
        try {
            DB::beginTransaction();

            $saleAmount = $transaction->amount;
            $feePercentage = $this->getFeePercentage();
            $platformFee = Commission::calculatePlatformFee($saleAmount, $feePercentage);
            $artistEarnings = Commission::calculateArtistEarnings($saleAmount, $platformFee);

            $commission = Commission::create([
                'transaction_id' => $transaction->id,
                'order_id' => null, // Can be linked if order exists
                'artist_id' => $transaction->seller_id,
                'sale_amount' => $saleAmount,
                'platform_fee' => $platformFee,
                'artist_earnings' => $artistEarnings,
                'platform_fee_percentage' => $feePercentage,
                'status' => 'pending',
            ]);

            DB::commit();

            Log::info('Commission created', [
                'commission_id' => $commission->id,
                'transaction_id' => $transaction->id,
                'artist_id' => $transaction->seller_id,
                'amount' => $saleAmount,
            ]);

            return $commission;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create commission', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create commission record from order.
     */
    public function createCommissionFromOrder(Order $order, int $artistId, float $saleAmount): Commission
    {
        try {
            DB::beginTransaction();

            $feePercentage = $this->getFeePercentage();
            $platformFee = Commission::calculatePlatformFee($saleAmount, $feePercentage);
            $artistEarnings = Commission::calculateArtistEarnings($saleAmount, $platformFee);

            $commission = Commission::create([
                'transaction_id' => null,
                'order_id' => $order->id,
                'artist_id' => $artistId,
                'sale_amount' => $saleAmount,
                'platform_fee' => $platformFee,
                'artist_earnings' => $artistEarnings,
                'platform_fee_percentage' => $feePercentage,
                'status' => 'pending',
            ]);

            DB::commit();

            Log::info('Commission created from order', [
                'commission_id' => $commission->id,
                'order_id' => $order->id,
                'artist_id' => $artistId,
            ]);

            return $commission;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create commission from order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get pending commissions for artist.
     */
    public function getPendingCommissionsForArtist(int $artistId)
    {
        return Commission::forArtist($artistId)
            ->pending()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get total pending earnings for artist.
     */
    public function getPendingEarningsForArtist(int $artistId): float
    {
        return Commission::forArtist($artistId)
            ->pending()
            ->sum('artist_earnings');
    }

    /**
     * Get total paid earnings for artist.
     */
    public function getPaidEarningsForArtist(int $artistId): float
    {
        return Commission::forArtist($artistId)
            ->paid()
            ->sum('artist_earnings');
    }

    /**
     * Get earnings statistics for artist dashboard.
     */
    public function getArtistEarningsStats(int $artistId): array
    {
        $pending = $this->getPendingEarningsForArtist($artistId);
        $paid = $this->getPaidEarningsForArtist($artistId);
        $total = $pending + $paid;

        $pendingCount = Commission::forArtist($artistId)->pending()->count();
        $paidCount = Commission::forArtist($artistId)->paid()->count();

        // Monthly earnings
        $monthlyEarnings = Commission::forArtist($artistId)
            ->paid()
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('artist_earnings');

        return [
            'pending_earnings' => $pending,
            'paid_earnings' => $paid,
            'total_earnings' => $total,
            'pending_count' => $pendingCount,
            'paid_count' => $paidCount,
            'monthly_earnings' => $monthlyEarnings,
            'platform_fee_percentage' => $this->getFeePercentage(),
        ];
    }

    /**
     * Create payout request for artist.
     */
    public function requestPayout(int $artistId, string $method = 'bank_transfer'): ?Payout
    {
        try {
            DB::beginTransaction();

            // Get pending commissions
            $pendingCommissions = $this->getPendingCommissionsForArtist($artistId);

            if ($pendingCommissions->isEmpty()) {
                return null;
            }

            $totalAmount = $pendingCommissions->sum('artist_earnings');
            $commissionIds = $pendingCommissions->pluck('id')->toArray();

            // Create payout
            $payout = Payout::create([
                'artist_id' => $artistId,
                'amount' => $totalAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'method' => $method,
                'commission_ids' => $commissionIds,
                'commission_count' => count($commissionIds),
                'requested_at' => now(),
            ]);

            DB::commit();

            Log::info('Payout requested', [
                'payout_id' => $payout->id,
                'artist_id' => $artistId,
                'amount' => $totalAmount,
            ]);

            return $payout;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create payout request', [
                'artist_id' => $artistId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Process payout (admin function).
     */
    public function processPayout(Payout $payout, int $adminId, string $paymentReference = null): bool
    {
        try {
            DB::beginTransaction();

            $payout->markAsCompleted($paymentReference);
            $payout->update([
                'processed_by' => $adminId,
            ]);

            DB::commit();

            Log::info('Payout processed', [
                'payout_id' => $payout->id,
                'admin_id' => $adminId,
                'payment_reference' => $paymentReference,
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process payout', [
                'payout_id' => $payout->id,
                'error' => $e->getMessage(),
            ]);
            
            $payout->markAsFailed($e->getMessage());
            return false;
        }
    }

    /**
     * Get all pending payouts for admin.
     */
    public function getPendingPayouts()
    {
        return Payout::pending()
            ->with('artist')
            ->orderBy('requested_at', 'asc')
            ->get();
    }

    /**
     * Get platform revenue statistics.
     */
    public function getPlatformRevenueStats(): array
    {
        $totalFees = Commission::sum('platform_fee');
        $pendingFees = Commission::pending()->sum('platform_fee');
        $paidFees = Commission::paid()->sum('platform_fee');

        $monthlyFees = Commission::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('platform_fee');

        return [
            'total_fees' => $totalFees,
            'pending_fees' => $pendingFees,
            'paid_fees' => $paidFees,
            'monthly_fees' => $monthlyFees,
        ];
    }

    /**
     * Set platform fee percentage (updates config at runtime, not persistent).
     */
    public function setFeePercentage(float $percentage): void
    {
        config(['platform.fee_percentage' => $percentage]);
    }
}
