<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'user', 'organizer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('user')) {
            return $transaction->booking && $user->id === $transaction->booking->user_id;
        }

        if ($user->hasRole('organizer')) {
            $organizerProfile = $user->organizerProfile;

            if (!$organizerProfile || !$transaction->booking) {
                return false;
            }

            foreach ($transaction->booking->items as $item) {
                if ($item->ticket && $item->ticket->event && $item->ticket->event->organizer_profile_id === $organizerProfile->id) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('user');
    }

    /**
     * Determine whether the user can update the model (e.g. upload payment proof).
     */
    public function update(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('user')) {
            return $transaction->booking && $user->id === $transaction->booking->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can verify (approve, reject) the transaction/payment.
     */
    public function verify(User $user, Transaction $transaction): bool
    {
        return $user->hasRole('admin');
    }
}
