<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\event;

interface eventRepositoryInterface
{
    /**
     * Get all events.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated events.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find an event by ID.
     *
     * @param int $id
     * @return event|null
     */
    public function findById(int $id): ?event;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return event
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): event;

    /**
     * Create a new event.
     *
     * @param array $data
     * @return event
     */
    public function create(array $data): event;

    /**
     * Update an existing event.
     *
     * @param event $event
     * @param array $data
     * @return bool
     */
    public function update(event $event, array $data): bool;

    /**
     * Delete an event.
     *
     * @param event $event
     * @return bool|null
     */
    public function delete(event $event): ?bool;

    /**
     * Search events by keyword.
     *
     * @param string $keyword
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator;

    /**
     * Filter events dynamically.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function filter(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get paginated published events.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPublished(int $perPage = 15): LengthAwarePaginator;

    /**
     * Get paginated draft events.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getDrafts(int $perPage = 15): LengthAwarePaginator;
}
