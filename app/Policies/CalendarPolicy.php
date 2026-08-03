<?php

namespace App\Policies;

use App\Models\eventSchedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, eventSchedule $schedule): bool
    {
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
    public function update(User $user, eventSchedule $schedule): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        $organizerProfile = $user->organizerProfile;
        return $organizerProfile && $schedule->event && (int) $organizerProfile->id === (int) $schedule->event->organizer_profile_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, eventSchedule $schedule): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        $organizerProfile = $user->organizerProfile;
        return $organizerProfile && $schedule->event && (int) $organizerProfile->id === (int) $schedule->event->organizer_profile_id;
    }
}
