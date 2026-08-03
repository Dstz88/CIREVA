<?php

namespace App\Policies;

use App\Models\CooperationAgreement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CooperationAgreementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CooperationAgreement $spk): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Organizers can view their own SPK
        $organizerProfile = $user->organizerProfile;
        
        return $organizerProfile && $organizerProfile->id === $spk->organizer_profile_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CooperationAgreement $spk): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CooperationAgreement $spk): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can digitally sign the SPK.
     */
    public function sign(User $user, CooperationAgreement $spk): bool
    {
        // Only the exact Organizer who owns this SPK is authorized to sign it
        $organizerProfile = $user->organizerProfile;
        
        return $organizerProfile && $organizerProfile->id === $spk->organizer_profile_id;
    }

    /**
     * Determine whether the user can verify (approve, request revision, reject) the SPK.
     */
    public function verify(User $user, CooperationAgreement $spk): bool
    {
        return $user->hasRole('admin');
    }
}
