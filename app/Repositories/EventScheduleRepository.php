<?php

namespace App\Repositories;

use App\Repositories\Contracts\eventScheduleRepositoryInterface;

use App\Models\eventSchedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class eventScheduleRepository implements eventScheduleRepositoryInterface
{
    /**
     * Get all event schedules.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return eventSchedule::all();
    }

    /**
     * Get paginated event schedules.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return eventSchedule::paginate($perPage);
    }

    /**
     * Find an event schedule by ID.
     *
     * @param int $id
     * @return eventSchedule|null
     */
    public function findById(int $id): ?eventSchedule
    {
        return eventSchedule::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return eventSchedule
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): eventSchedule
    {
        return eventSchedule::findOrFail($id);
    }

    /**
     * Create a new event schedule.
     *
     * @param array $data
     * @return eventSchedule
     */
    public function create(array $data): eventSchedule
    {
        return eventSchedule::create($data);
    }

    /**
     * Update an existing event schedule.
     *
     * @param eventSchedule $schedule
     * @param array $data
     * @return bool
     */
    public function update(eventSchedule $schedule, array $data): bool
    {
        return $schedule->update($data);
    }

    /**
     * Delete an event schedule.
     *
     * @param eventSchedule $schedule
     * @return bool|null
     */
    public function delete(eventSchedule $schedule): ?bool
    {
        return $schedule->delete();
    }

    /**
     * Get schedules for a specific event.
     *
     * @param int $eventId
     * @return Collection
     */
    public function getByevent(int $eventId): Collection
    {
        return eventSchedule::where('event_id', $eventId)
            ->orderBy('start_datetime', 'asc')
            ->get();
    }

    /**
     * Get upcoming event schedules paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getUpcoming(int $perPage = 15): LengthAwarePaginator
    {
        return eventSchedule::where('start_datetime', '>', now())
            ->orderBy('start_datetime', 'asc')
            ->paginate($perPage);
    }

    /**
     * Check if a schedule conflicts with an existing schedule at the same location.
     *
     * @param int $locationId
     * @param string $start
     * @param string $end
     * @param int|null $excludeScheduleId
     * @return bool
     */
    public function hasConflict(int $locationId, string $start, string $end, ?int $excludeScheduleId = null): bool
    {
        $query = eventSchedule::whereHas('event', function ($q) use ($locationId) {
            $q->where('location_id', $locationId);
        })->where(function ($q) use ($start, $end) {
            $q->whereBetween('start_datetime', [$start, $end])
                ->orWhereBetween('end_datetime', [$start, $end])
                ->orWhere(function ($subQ) use ($start, $end) {
                    $subQ->where('start_datetime', '<=', $start)
                        ->where('end_datetime', '>=', $end);
                });
        });

        if ($excludeScheduleId) {
            $query->where('id', '!=', $excludeScheduleId);
        }

        return $query->exists();
    }
}
