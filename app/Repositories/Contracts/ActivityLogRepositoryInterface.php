<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\ActivityLog;

interface ActivityLogRepositoryInterface
{
    /**
     * Get all activity logs.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated activity logs.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find an activity log by ID.
     *
     * @param int $id
     * @return ActivityLog|null
     */
    public function findById(int $id): ?ActivityLog;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return ActivityLog
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): ActivityLog;

    /**
     * Create a new activity log.
     *
     * @param array $data
     * @return ActivityLog
     */
    public function create(array $data): ActivityLog;

    /**
     * Update an existing activity log.
     *
     * @param ActivityLog $log
     * @param array $data
     * @return bool
     */
    public function update(ActivityLog $log, array $data): bool;

    /**
     * Delete an activity log.
     *
     * @param ActivityLog $log
     * @return bool|null
     */
    public function delete(ActivityLog $log): ?bool;

    /**
     * Find activity logs by user ID.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find activity logs by module.
     *
     * @param string $module
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByModule(string $module, int $perPage = 15): LengthAwarePaginator;
}

