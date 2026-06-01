<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any transactions.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the transaction.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        // Admin can view any transaction
        if ($user->isAdmin()) {
            return true;
        }

        // Buyer and seller can view their own transactions
        return $user->id === $transaction->buyer_id || $user->id === $transaction->seller_id;
    }

    /**
     * Determine whether the user can create transactions.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the transaction.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the transaction.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can refund the transaction.
     */
    public function refund(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the transaction.
     */
    public function restore(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the transaction.
     */
    public function forceDelete(User $user, Transaction $transaction): bool
    {
        return $user->isAdmin();
    }
}
