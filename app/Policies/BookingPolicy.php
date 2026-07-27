<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    private function getRoleName(User $user): string
    {
        if (is_object($user->role)) {
            return strtolower((string)($user->role->name ?? ''));
        }
        return strtolower((string)($user->role ?? ''));
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return !empty($this->getRoleName($user));
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        $roleName = $this->getRoleName($user);

        // Admin has universal read access
        if ($roleName === 'admin') {
            return true;
        }

        // User can view their own bookings
        if ($user->id === $booking->user_id) {
            return true;
        }

        // Organizer can monitor bookings if they contain tickets for their events
        if ($roleName === 'organizer') {
            $organizerProfile = $user->organizerProfile;

            if (!$organizerProfile) {
                return false;
            }

            foreach ($booking->items as $item) {
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
        return !empty($this->getRoleName($user));
    }

    /**
     * Determine whether the user can update the model (e.g. process payment status manually).
     */
    public function update(User $user, Booking $booking): bool
    {
        if ($this->getRoleName($user) === 'admin') {
            return true;
        }

        return $user->id === $booking->user_id;
    }

    /**
     * Determine whether the user can delete (cancel) the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        if ($this->getRoleName($user) === 'admin') {
            return true;
        }

        return $user->id === $booking->user_id;
    }
}
