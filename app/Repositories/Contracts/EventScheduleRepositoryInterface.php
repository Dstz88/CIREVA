<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\eventSchedule;

interface eventScheduleRepositoryInterface
{
    /**
     * Get all event schedules.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated event schedules.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find an event schedule by ID.
     *
     * @param int $id
     * @return eventSchedule|null
     */
    public function findById(int $id): ?eventSchedule;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return eventSchedule
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): eventSchedule;

    /**
     * Create a new event schedule.
     *
     * @param array $data
     * @return eventSchedule
     */
    public function create(array $data): eventSchedule;

    /**
     * Update an existing event schedule.
     *
     * @param eventSchedule $schedule
     * @param array $data
     * @return bool
     */
    public function update(eventSchedule $schedule, array $data): bool;

    /**
     * Delete an event schedule.
     *
     * @param eventSchedule $schedule
     * @return bool|null
     */
    public function delete(eventSchedule $schedule): ?bool;

    /**
     * Get schedules for a specific event.
     *
     * @param int $eventId
     * @return Collection
     */
    public function getByevent(int $eventId): Collection;

    /**
     * Get upcoming event schedules paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUpcoming(int $perPage = 15): LengthAwarePaginator;

    /**
     * Check if a schedule conflicts with an existing schedule at the same location.
     *
     * @param int $locationId
     * @param string $start
     * @param string $end
     * @param int|null $excludeScheduleId
     * @return bool
     */
    public function hasConflict(int $locationId, string $start, string $end, ?int $excludeScheduleId = NULL): bool;
}
