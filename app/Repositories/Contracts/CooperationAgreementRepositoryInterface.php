<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\CooperationAgreement;

interface CooperationAgreementRepositoryInterface
{
    /**
     * Get all cooperation agreements.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated cooperation agreements.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a cooperation agreement by ID.
     *
     * @param int $id
     * @return CooperationAgreement|null
     */
    public function findById(int $id): ?CooperationAgreement;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return CooperationAgreement
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): CooperationAgreement;

    /**
     * Create a new cooperation agreement.
     *
     * @param array $data
     * @return CooperationAgreement
     */
    public function create(array $data): CooperationAgreement;

    /**
     * Update an existing cooperation agreement.
     *
     * @param CooperationAgreement $agreement
     * @param array $data
     * @return bool
     */
    public function update(CooperationAgreement $agreement, array $data): bool;

    /**
     * Delete a cooperation agreement.
     *
     * @param CooperationAgreement $agreement
     * @return bool|null
     */
    public function delete(CooperationAgreement $agreement): ?bool;

    /**
     * Get all agreements belonging to a specific organizer profile.
     *
     * @param int $organizerProfileId
     * @return Collection
     */
    public function getByOrganizerProfile(int $organizerProfileId): Collection;
}

