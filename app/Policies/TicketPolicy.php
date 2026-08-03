<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone (including guests) can view ticket listings for an event
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Ticket $ticket): bool
    {
        // Anyone can view a specific ticket's details
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'organizer']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Organizer can only update tickets that belong to their own event
        $organizerProfile = $user->organizerProfile;

        return $organizerProfile && $ticket->event && $organizerProfile->id === $ticket->event->organizer_profile_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Organizer can only delete tickets that belong to their own event
        $organizerProfile = $user->organizerProfile;

        return $organizerProfile && $ticket->event && $organizerProfile->id === $ticket->event->organizer_profile_id;
    }

    /**
     * Determine whether the user can buy the ticket.
     */
    public function buy(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('user');
    }

    /**
     * Determine whether the user can activate or deactivate the ticket.
     */
    public function manageStatus(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        $organizerProfile = $user->organizerProfile;

        return $organizerProfile && $ticket->event && $organizerProfile->id === $ticket->event->organizer_profile_id;
    }
}
