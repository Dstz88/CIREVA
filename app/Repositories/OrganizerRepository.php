<?php

namespace App\Repositories;

use App\Repositories\Contracts\OrganizerRepositoryInterface;

use App\Models\OrganizerProfile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizerRepository implements OrganizerRepositoryInterface
{
    /**
     * Get all organizer profiles.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return OrganizerProfile::all();
    }

    /**
     * Get paginated organizer profiles.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return OrganizerProfile::paginate($perPage);
    }

    /**
     * Find an organizer profile by ID.
     *
     * @param int $id
     * @return OrganizerProfile|null
     */
    public function findById(int $id): ?OrganizerProfile
    {
        return OrganizerProfile::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return OrganizerProfile
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): OrganizerProfile
    {
        return OrganizerProfile::findOrFail($id);
    }

    /**
     * Create a new organizer profile.
     *
     * @param array $data
     * @return OrganizerProfile
     */
    public function create(array $data): OrganizerProfile
    {
        return OrganizerProfile::create($data);
    }

    /**
     * Update an existing organizer profile.
     *
     * @param OrganizerProfile $organizer
     * @param array $data
     * @return bool
     */
    public function update(OrganizerProfile $organizer, array $data): bool
    {
        return $organizer->update($data);
    }

    /**
     * Delete an organizer profile.
     *
     * @param OrganizerProfile $organizer
     * @return bool|null
     */
    public function delete(OrganizerProfile $organizer): ?bool
    {
        return $organizer->delete();
    }

    /**
     * Get paginated approved organizers (Query Helper).
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getApproved(int $perPage = 15): LengthAwarePaginator
    {
        return OrganizerProfile::approved()->paginate($perPage);
    }

    /**
     * Get paginated pending organizers (Query Helper).
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPending(int $perPage = 15): LengthAwarePaginator
    {
        return OrganizerProfile::pending()->paginate($perPage);
    }
}

