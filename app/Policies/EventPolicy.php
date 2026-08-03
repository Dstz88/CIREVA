<?php

namespace App\Policies;

use App\Models\event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class eventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Everyone (including guests) can view event listings
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, event $event): bool
    {
        // Everyone (including guests) can view event details
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Admin or Organizer can create new events
        return $user->hasRole(['admin', 'organizer']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, event $event): bool
    {
        // Admin has universal update rights
        if ($user->hasRole('admin')) {
            return true;
        }

        // Organizer can only update their own event
        $organizerProfile = $user->organizerProfile;

        return $organizerProfile && $organizerProfile->id === $event->organizer_profile_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, event $event): bool
    {
        // Admin has universal delete rights
        if ($user->hasRole('admin')) {
            return true;
        }

        // Organizer can only delete their own event
        $organizerProfile = $user->organizerProfile;

        return $organizerProfile && $organizerProfile->id === $event->organizer_profile_id;
    }

    /**
     * Determine whether the user can verify (approve, request revision, reject) the model.
     */
    public function verify(User $user, event $event): bool
    {
        // Only Admin can verify/approve events
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can submit the event for review.
     */
    public function submit(User $user, event $event): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Organizer can only submit their own event
        $organizerProfile = $user->organizerProfile;

        return $organizerProfile && $organizerProfile->id === $event->organizer_profile_id;
    }

    /**
     * Determine whether the user can publish the event.
     */
    public function publish(User $user, event $event): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Organizer can only publish their own event
        $organizerProfile = $user->organizerProfile;

        return $organizerProfile && $organizerProfile->id === $event->organizer_profile_id;
    }

    /**
     * Determine whether an admin can approve or reject events.
     */
    public function adminApprove(User $user, event $event): bool
    {
        return $user->hasRole('admin');
    }
}
