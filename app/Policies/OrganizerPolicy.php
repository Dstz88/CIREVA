<?php

namespace App\Policies;

use App\Models\OrganizerProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrganizerProfile $organizerProfile): bool
    {
        if ($user->role && strtolower((string)$user->role->name) === 'admin') {
            return true;
        }

        return (int)$user->id === (int)$organizerProfile->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrganizerProfile $organizerProfile): bool
    {
        if ($user->role && strtolower((string)$user->role->name) === 'admin') {
            return true;
        }

        return (int)$user->id === (int)$organizerProfile->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrganizerProfile $organizerProfile): bool
    {
        // Only Admin can delete an organizer profile
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Determine whether the admin can verify (approve/reject/suspend) the model.
     */
    public function verify(User $user, OrganizerProfile $organizerProfile): bool
    {
        // Only Admin can verify profiles
        return $user->role && $user->role->name === 'Admin';
    }
}
