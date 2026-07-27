<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\OrganizerProfile;

interface OrganizerRepositoryInterface
{
    /**
     * Get all organizer profiles.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated organizer profiles.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find an organizer profile by ID.
     *
     * @param int $id
     * @return OrganizerProfile|null
     */
    public function findById(int $id): ?OrganizerProfile;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return OrganizerProfile
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): OrganizerProfile;

    /**
     * Create a new organizer profile.
     *
     * @param array $data
     * @return OrganizerProfile
     */
    public function create(array $data): OrganizerProfile;

    /**
     * Update an existing organizer profile.
     *
     * @param OrganizerProfile $organizer
     * @param array $data
     * @return bool
     */
    public function update(OrganizerProfile $organizer, array $data): bool;

    /**
     * Delete an organizer profile.
     *
     * @param OrganizerProfile $organizer
     * @return bool|null
     */
    public function delete(OrganizerProfile $organizer): ?bool;

    /**
     * Get paginated approved organizers (Query Helper).
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getApproved(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get paginated pending organizers (Query Helper).
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPending(int $perPage = 15): LengthAwarePaginator;
}

