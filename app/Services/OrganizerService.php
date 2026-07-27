<?php

namespace App\Services;

use App\Repositories\Contracts\OrganizerRepositoryInterface;
use App\Models\OrganizerProfile;
use App\Enums\OrganizerStatus;
use Exception;
use InvalidArgumentException;

class OrganizerService
{
    protected OrganizerRepositoryInterface $organizerRepository;

    public function __construct(OrganizerRepositoryInterface $organizerRepository)
    {
        $this->organizerRepository = $organizerRepository;
    }

    /**
     * Register a new organizer profile.
     * Initial state is Pending.
     *
     * @param int $userId
     * @param array $data
     * @return OrganizerProfile
     */
    public function registerProfile(int $userId, array $data): OrganizerProfile
    {
        $data['user_id'] = $userId;
        $data['status'] = OrganizerStatus::Pending;
        
        return $this->organizerRepository->create($data);
    }

    /**
     * Update an organizer profile (only allowed in certain states).
     *
     * @param int $organizerId
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateProfile(int $organizerId, array $data): bool
    {
        $organizer = $this->organizerRepository->findOrFail($organizerId);

        // Business Rule: Profile cannot be updated while under review to avoid verification conflicts
        if ($organizer->status === OrganizerStatus::UnderReview) {
            throw new Exception("Cannot update profile while it is under review.");
        }

        return $this->organizerRepository->update($organizer, $data);
    }

    /**
     * Submit the organizer profile for review.
     * State transition: Pending / Rejected -> Under Review
     *
     * @param int $organizerId
     * @return bool
     * @throws Exception
     */
    public function submitForReview(int $organizerId): bool
    {
        $organizer = $this->organizerRepository->findOrFail($organizerId);

        // Business Rule: Profile must be in pending or rejected status to be submitted
        if ($organizer->status !== OrganizerStatus::Pending && $organizer->status !== OrganizerStatus::Rejected) {
            throw new Exception("Organizer profile can only be submitted for review if it is pending or rejected.");
        }

        return $this->organizerRepository->update($organizer, [
            'status' => OrganizerStatus::UnderReview
        ]);
    }

    /**
     * Approve the organizer profile.
     * State transition: Under Review -> Approved
     *
     * @param int $organizerId
     * @param int $adminId
     * @return bool
     * @throws Exception
     */
    public function approve(int $organizerId, int $adminId): bool
    {
        $organizer = $this->organizerRepository->findOrFail($organizerId);

        if ($organizer->status !== OrganizerStatus::UnderReview) {
            throw new Exception("Only profiles under review can be approved.");
        }

        return $this->organizerRepository->update($organizer, [
            'status' => OrganizerStatus::Approved,
            'verified_by' => $adminId,
            'verified_at' => now(),
            'rejection_reason' => null
        ]);
    }

    /**
     * Reject the organizer profile.
     * State transition: Under Review -> Rejected
     *
     * @param int $organizerId
     * @param int $adminId
     * @param string $reason
     * @return bool
     * @throws Exception
     */
    public function reject(int $organizerId, int $adminId, string $reason): bool
    {
        if (empty(trim($reason))) {
            throw new InvalidArgumentException("Rejection reason cannot be empty.");
        }

        $organizer = $this->organizerRepository->findOrFail($organizerId);

        if ($organizer->status !== OrganizerStatus::UnderReview) {
            throw new Exception("Only profiles under review can be rejected.");
        }

        return $this->organizerRepository->update($organizer, [
            'status' => OrganizerStatus::Rejected,
            'verified_by' => $adminId,
            'verified_at' => now(),
            'rejection_reason' => $reason
        ]);
    }

    /**
     * Suspend an active organizer profile.
     * State transition: Approved -> Suspended
     *
     * @param int $organizerId
     * @param string $reason
     * @return bool
     * @throws Exception
     */
    public function suspend(int $organizerId, string $reason): bool
    {
        if (empty(trim($reason))) {
            throw new InvalidArgumentException("Suspension reason cannot be empty.");
        }

        $organizer = $this->organizerRepository->findOrFail($organizerId);

        if ($organizer->status !== OrganizerStatus::Approved) {
            throw new Exception("Only approved organizers can be suspended.");
        }

        return $this->organizerRepository->update($organizer, [
            'status' => OrganizerStatus::Suspended,
            'rejection_reason' => $reason
        ]);
    }
}
