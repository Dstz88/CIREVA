<?php

namespace App\Repositories;

use App\Repositories\Contracts\CooperationAgreementRepositoryInterface;

use App\Models\CooperationAgreement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CooperationAgreementRepository implements CooperationAgreementRepositoryInterface
{
    /**
     * Get all cooperation agreements.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return CooperationAgreement::all();
    }

    /**
     * Get paginated cooperation agreements.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return CooperationAgreement::paginate($perPage);
    }

    /**
     * Find a cooperation agreement by ID.
     *
     * @param int $id
     * @return CooperationAgreement|null
     */
    public function findById(int $id): ?CooperationAgreement
    {
        return CooperationAgreement::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return CooperationAgreement
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): CooperationAgreement
    {
        return CooperationAgreement::findOrFail($id);
    }

    /**
     * Create a new cooperation agreement.
     *
     * @param array $data
     * @return CooperationAgreement
     */
    public function create(array $data): CooperationAgreement
    {
        return CooperationAgreement::create($data);
    }

    /**
     * Update an existing cooperation agreement.
     *
     * @param CooperationAgreement $agreement
     * @param array $data
     * @return bool
     */
    public function update(CooperationAgreement $agreement, array $data): bool
    {
        return $agreement->update($data);
    }

    /**
     * Delete a cooperation agreement.
     *
     * @param CooperationAgreement $agreement
     * @return bool|null
     */
    public function delete(CooperationAgreement $agreement): ?bool
    {
        return $agreement->delete();
    }

    /**
     * Get all agreements belonging to a specific organizer profile.
     *
     * @param int $organizerProfileId
     * @return Collection
     */
    public function getByOrganizerProfile(int $organizerProfileId): Collection
    {
        return CooperationAgreement::where('organizer_profile_id', $organizerProfileId)->get();
    }
}

