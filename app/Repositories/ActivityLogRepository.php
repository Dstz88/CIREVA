<?php

namespace App\Repositories;

use App\Repositories\Contracts\ActivityLogRepositoryInterface;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    /**
     * Get all activity logs.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return ActivityLog::all();
    }

    /**
     * Get paginated activity logs.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return ActivityLog::latest('created_at')->paginate($perPage);
    }

    /**
     * Find an activity log by ID.
     *
     * @param int $id
     * @return ActivityLog|null
     */
    public function findById(int $id): ?ActivityLog
    {
        return ActivityLog::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return ActivityLog
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): ActivityLog
    {
        return ActivityLog::findOrFail($id);
    }

    /**
     * Create a new activity log.
     *
     * @param array $data
     * @return ActivityLog
     */
    public function create(array $data): ActivityLog
    {
        return ActivityLog::create($data);
    }

    /**
     * Update an existing activity log.
     *
     * @param ActivityLog $log
     * @param array $data
     * @return bool
     */
    public function update(ActivityLog $log, array $data): bool
    {
        return $log->update($data);
    }

    /**
     * Delete an activity log.
     *
     * @param ActivityLog $log
     * @return bool|null
     */
    public function delete(ActivityLog $log): ?bool
    {
        return $log->delete();
    }

    /**
     * Find activity logs by user ID.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return ActivityLog::where('user_id', $userId)
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Find activity logs by module.
     *
     * @param string $module
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByModule(string $module, int $perPage = 15): LengthAwarePaginator
    {
        return ActivityLog::where('module', $module)
            ->latest('created_at')
            ->paginate($perPage);
    }
}

