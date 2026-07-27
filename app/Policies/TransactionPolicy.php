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
        // Admin, User, and Organizer all have access to lists (scoping is done in controller)
        return $user->role && in_array($user->role->name, ['Admin', 'User', 'Organizer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        // Admin has universal read access
        if ($user->role && $user->role->name === 'Admin') {
            return true;
        }

        // User can only view transactions related to their own bookings
        if ($user->role && $user->role->name === 'User') {
            return $transaction->booking && $user->id === $transaction->booking->user_id;
        }

        // Organizer can view transactions if the booking contains tickets for their events (Revenue Monitoring)
        if ($user->role && $user->role->name === 'Organizer') {
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
        // Only normal users create transactions (when initiating payment)
        return $user->role && $user->role->name === 'User';
    }

    /**
     * Determine whether the user can update the model (e.g. upload payment proof).
     */
    public function update(User $user, Transaction $transaction): bool
    {
        if ($user->role && $user->role->name === 'Admin') {
            return true;
        }

        // Users can update (e.g. attach proof) to their own transactions
        if ($user->role && $user->role->name === 'User') {
            return $transaction->booking && $user->id === $transaction->booking->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        // Only Admin can delete transactions
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can verify (approve, reject) the transaction/payment.
     */
    public function verify(User $user, Transaction $transaction): bool
    {
        // Only Admin is authorized to verify and confirm payment statuses
        return $user->role && $user->role->name === 'Admin';
    }
}
