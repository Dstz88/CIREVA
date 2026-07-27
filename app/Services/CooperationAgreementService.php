<?php

namespace App\Services;

use App\Repositories\Contracts\CooperationAgreementRepositoryInterface;
use App\Models\CooperationAgreement;
use App\Enums\SpkStatus;
use Exception;
use InvalidArgumentException;

class CooperationAgreementService
{
    protected CooperationAgreementRepositoryInterface $spkRepository;

    public function __construct(CooperationAgreementRepositoryInterface $spkRepository)
    {
        $this->spkRepository = $spkRepository;
    }

    /**
     * Generate SPK for an organizer.
     * State transition: Null -> Generated
     *
     * @param int $organizerId
     * @param array $data
     * @return CooperationAgreement
     */
    public function generateSpk(int $organizerId, array $data): CooperationAgreement
    {
        $data['organizer_profile_id'] = $organizerId;
        $data['status'] = SpkStatus::Generated;
        
        return $this->spkRepository->create($data);
    }

    /**
     * Mark SPK as ready for signature.
     * State transition: Draft / Generated -> Pending Signature
     *
     * @param int $spkId
     * @return bool
     * @throws Exception
     */
    public function markAsPendingSignature(int $spkId): bool
    {
        $spk = $this->spkRepository->findOrFail($spkId);

        if ($spk->status !== SpkStatus::Generated && $spk->status !== SpkStatus::Draft) {
            throw new Exception("Only Draft or Generated SPK can be marked for signature.");
        }

        return $this->spkRepository->update($spk, ['status' => SpkStatus::PendingSignature]);
    }

    /**
     * Sign the SPK (Digital Signature by Organizer).
     * State transition: Pending Signature -> Signed
     *
     * @param int $spkId
     * @return bool
     * @throws Exception
     */
    public function signSpk(int $spkId): bool
    {
        $spk = $this->spkRepository->findOrFail($spkId);

        if ($spk->status !== SpkStatus::PendingSignature) {
            throw new Exception("SPK is not pending signature.");
        }

        return $this->spkRepository->update($spk, [
            'status' => SpkStatus::Signed,
            'signed_at' => now(),
        ]);
    }

    /**
     * Submit signed SPK for admin review.
     * State transition: Signed / Revision Required -> Under Review
     *
     * @param int $spkId
     * @return bool
     * @throws Exception
     */
    public function submitForReview(int $spkId): bool
    {
        $spk = $this->spkRepository->findOrFail($spkId);

        if ($spk->status !== SpkStatus::Signed && $spk->status !== SpkStatus::RevisionRequired) {
            throw new Exception("Only signed or revision required SPK can be submitted for review.");
        }

        return $this->spkRepository->update($spk, ['status' => SpkStatus::UnderReview]);
    }

    /**
     * Approve the SPK by admin.
     * State transition: Under Review -> Approved
     *
     * @param int $spkId
     * @param int $adminId
     * @return bool
     * @throws Exception
     */
    public function approve(int $spkId, int $adminId): bool
    {
        $spk = $this->spkRepository->findOrFail($spkId);

        if ($spk->status !== SpkStatus::UnderReview) {
            throw new Exception("Only SPK under review can be approved.");
        }

        return $this->spkRepository->update($spk, [
            'status' => SpkStatus::Approved,
            'approved_by' => $adminId,
            'approved_at' => now(),
            'rejected_reason' => null
        ]);
    }

    /**
     * Reject the SPK.
     * State transition: Under Review -> Rejected
     *
     * @param int $spkId
     * @param int $adminId
     * @param string $reason
     * @return bool
     * @throws Exception
     */
    public function reject(int $spkId, int $adminId, string $reason): bool
    {
        if (empty(trim($reason))) {
            throw new InvalidArgumentException("Rejection reason cannot be empty.");
        }

        $spk = $this->spkRepository->findOrFail($spkId);

        if ($spk->status !== SpkStatus::UnderReview) {
            throw new Exception("Only SPK under review can be rejected.");
        }

        return $this->spkRepository->update($spk, [
            'status' => SpkStatus::Rejected,
            'approved_by' => $adminId,
            'approved_at' => now(),
            'rejected_reason' => $reason
        ]);
    }

    /**
     * Request revision for the SPK.
     * State transition: Under Review -> Revision Required
     *
     * @param int $spkId
     * @param int $adminId
     * @param string $reason
     * @return bool
     * @throws Exception
     */
    public function requestRevision(int $spkId, int $adminId, string $reason): bool
    {
        if (empty(trim($reason))) {
            throw new InvalidArgumentException("Revision reason cannot be empty.");
        }

        $spk = $this->spkRepository->findOrFail($spkId);

        if ($spk->status !== SpkStatus::UnderReview) {
            throw new Exception("Only SPK under review can be flagged for revision.");
        }

        return $this->spkRepository->update($spk, [
            'status' => SpkStatus::RevisionRequired,
            'approved_by' => $adminId,
            'rejected_reason' => $reason
        ]);
    }
}
