<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\eventLocation;

interface eventLocationRepositoryInterface
{
    /**
     * Get all event locations.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated event locations.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a location by ID.
     *
     * @param int $id
     * @return eventLocation|null
     */
    public function findById(int $id): ?eventLocation;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return eventLocation
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): eventLocation;

    /**
     * Create a new location.
     *
     * @param array $data
     * @return eventLocation
     */
    public function create(array $data): eventLocation;

    /**
     * Update an existing location.
     *
     * @param eventLocation $location
     * @param array $data
     * @return bool
     */
    public function update(eventLocation $location, array $data): bool;

    /**
     * Delete a location.
     *
     * @param eventLocation $location
     * @return bool|null
     */
    public function delete(eventLocation $location): ?bool;
}
