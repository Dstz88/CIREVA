<?php

namespace App\Repositories;

use App\Repositories\Contracts\eventLocationRepositoryInterface;

use App\Models\eventLocation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class eventLocationRepository implements eventLocationRepositoryInterface
{
    /**
     * Get all event locations.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return eventLocation::all();
    }

    /**
     * Get paginated event locations.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return eventLocation::paginate($perPage);
    }

    /**
     * Find a location by ID.
     *
     * @param int $id
     * @return eventLocation|null
     */
    public function findById(int $id): ?eventLocation
    {
        return eventLocation::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return eventLocation
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): eventLocation
    {
        return eventLocation::findOrFail($id);
    }

    /**
     * Create a new location.
     *
     * @param array $data
     * @return eventLocation
     */
    public function create(array $data): eventLocation
    {
        return eventLocation::create($data);
    }

    /**
     * Update an existing location.
     *
     * @param eventLocation $location
     * @param array $data
     * @return bool
     */
    public function update(eventLocation $location, array $data): bool
    {
        return $location->update($data);
    }

    /**
     * Delete a location.
     *
     * @param eventLocation $location
     * @return bool|null
     */
    public function delete(eventLocation $location): ?bool
    {
        return $location->delete();
    }
}
