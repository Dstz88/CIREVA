<?php

namespace App\Services;

use App\Repositories\Contracts\eventScheduleRepositoryInterface;
use App\Repositories\Contracts\eventRepositoryInterface;
use App\Repositories\Contracts\BookingItemRepositoryInterface;
use App\Models\eventSchedule;
use App\Enums\ScheduleStatus;
use Exception;

class CalendarService
{
    protected eventScheduleRepositoryInterface $scheduleRepository;
    protected eventRepositoryInterface $eventRepository;
    protected BookingItemRepositoryInterface $bookingItemRepository;

    public function __construct(
        eventScheduleRepositoryInterface $scheduleRepository,
        eventRepositoryInterface $eventRepository,
        BookingItemRepositoryInterface $bookingItemRepository
    ) {
        $this->scheduleRepository = $scheduleRepository;
        $this->eventRepository = $eventRepository;
        $this->bookingItemRepository = $bookingItemRepository;
    }

    /**
     * Create a new event schedule.
     * Applies conflict validation.
     * State transition: Null -> Scheduled
     *
     * @param int $eventId
     * @param array $data
     * @return eventSchedule
     * @throws Exception
     */
    public function createSchedule(int $eventId, array $data): eventSchedule
    {
        $event = $this->eventRepository->findOrFail($eventId);

        // Conflict Validation
        if ($this->scheduleRepository->hasConflict(
            $event->location_id,
            $data['start_datetime'],
            $data['end_datetime']
        )) {
            throw new Exception("Jadwal bentrok dengan jadwal lain di lokasi yang sama.");
        }

        $data['event_id'] = $eventId;
        $data['status'] = ScheduleStatus::Scheduled;

        return $this->scheduleRepository->create($data);
    }

    /**
     * Update an event schedule.
     *
     * @param int $scheduleId
     * @param array $data
     * @param bool $isAdmin
     * @return bool
     * @throws Exception
     */
    public function updateSchedule(int $scheduleId, array $data, bool $isAdmin = false): bool
    {
        $schedule = $this->scheduleRepository->findOrFail($scheduleId);

        // Business Rule: Perubahan jadwal Published memerlukan persetujuan Admin
        if ($schedule->status === ScheduleStatus::Published && !$isAdmin) {
            throw new Exception("Perubahan jadwal yang sudah Published memerlukan persetujuan Admin.");
        }

        $event = $schedule->event;

        // Check for conflicts if dates are updated
        $start = $data['start_datetime'] ?? $schedule->start_datetime;
        $end = $data['end_datetime'] ?? $schedule->end_datetime;

        if ($this->scheduleRepository->hasConflict($event->location_id, $start, $end, $scheduleId)) {
            throw new Exception("Jadwal bentrok dengan jadwal lain di lokasi yang sama.");
        }

        return $this->scheduleRepository->update($schedule, $data);
    }

    /**
     * Delete a schedule.
     *
     * @param int $scheduleId
     * @return bool
     * @throws Exception
     */
    public function deleteSchedule(int $scheduleId): bool
    {
        $schedule = $this->scheduleRepository->findOrFail($scheduleId);

        // Business Rule: Jadwal dengan booking tidak dapat dihapus.
        // We verify if the associated event has any bookings (via tickets)
        if ($this->bookingItemRepository->existsForevent($schedule->event_id)) {
            throw new Exception("Jadwal tidak dapat dihapus karena event sudah memiliki booking.");
        }

        return $this->scheduleRepository->delete($schedule);
    }

    /**
     * Publish a schedule.
     * State transition: Scheduled -> Published
     *
     * @param int $scheduleId
     * @return bool
     * @throws Exception
     */
    public function publishSchedule(int $scheduleId): bool
    {
        $schedule = $this->scheduleRepository->findOrFail($scheduleId);

        if ($schedule->status !== ScheduleStatus::Scheduled) {
            throw new Exception("Only scheduled items can be published.");
        }

        return $this->scheduleRepository->update($schedule, ['status' => ScheduleStatus::Published]);
    }

    /**
     * Mark schedule as ongoing.
     * State transition: Published -> Ongoing
     *
     * @param int $scheduleId
     * @return bool
     * @throws Exception
     */
    public function markAsOngoing(int $scheduleId): bool
    {
        $schedule = $this->scheduleRepository->findOrFail($scheduleId);

        if ($schedule->status !== ScheduleStatus::Published) {
            throw new Exception("Only published schedules can be marked as ongoing.");
        }

        return $this->scheduleRepository->update($schedule, ['status' => ScheduleStatus::Ongoing]);
    }

    /**
     * Mark schedule as finished.
     * State transition: Ongoing -> Finished
     *
     * @param int $scheduleId
     * @return bool
     * @throws Exception
     */
    public function markAsFinished(int $scheduleId): bool
    {
        $schedule = $this->scheduleRepository->findOrFail($scheduleId);

        if ($schedule->status !== ScheduleStatus::Ongoing) {
            throw new Exception("Only ongoing schedules can be marked as finished.");
        }

        return $this->scheduleRepository->update($schedule, ['status' => ScheduleStatus::Finished]);
    }
}
