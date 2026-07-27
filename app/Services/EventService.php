<?php

namespace App\Services;

use App\Repositories\Contracts\eventRepositoryInterface;
use App\Repositories\Contracts\OrganizerRepositoryInterface;
use App\Repositories\Contracts\CooperationAgreementRepositoryInterface;
use App\Repositories\Contracts\eventScheduleRepositoryInterface;
use App\Models\event;
use App\Enums\eventStatus;
use App\Enums\OrganizerStatus;
use App\Enums\SpkStatus;
use Exception;
use Illuminate\Support\Str;

class eventService
{
    protected eventRepositoryInterface $eventRepository;
    protected OrganizerRepositoryInterface $organizerRepository;
    protected CooperationAgreementRepositoryInterface $spkRepository;
    protected eventScheduleRepositoryInterface $scheduleRepository;

    public function __construct(
        eventRepositoryInterface $eventRepository,
        OrganizerRepositoryInterface $organizerRepository,
        CooperationAgreementRepositoryInterface $spkRepository,
        eventScheduleRepositoryInterface $scheduleRepository
    ) {
        $this->eventRepository = $eventRepository;
        $this->organizerRepository = $organizerRepository;
        $this->spkRepository = $spkRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * Create a new event draft.
     * State transition: Null -> Draft
     *
     * @param int $organizerId
     * @param array $data
     * @return event
     */
    public function createDraft(int $organizerId, array $data): event
    {
        $data['organizer_profile_id'] = $organizerId;
        $data['status'] = eventStatus::Draft;

        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . time();
        }

        return $this->eventRepository->create($data);
    }

    /**
     * Update an event. Only allowed if not published/ongoing/finished/archived 
     * unless explicitly permitted by admin.
     *
     * @param int $eventId
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateevent(int $eventId, array $data): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        // Restrict updates for active/past events
        $restrictedStates = [
            eventStatus::Published,
            eventStatus::Ongoing,
            eventStatus::Finished,
            eventStatus::Archived
        ];

        if (in_array($event->status, $restrictedStates)) {
            throw new Exception("Cannot update event in its current state.");
        }

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']) . '-' . time();
        }

        return $this->eventRepository->update($event, $data);
    }

    /**
     * Submit an event for review.
     * State transition: Draft / Revision Required -> Submitted
     *
     * @param int $eventId
     * @return bool
     * @throws Exception
     */
    public function submit(int $eventId): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        if ($event->status !== eventStatus::Draft && $event->status !== eventStatus::RevisionRequired) {
            throw new Exception("Only Draft or Revision Required events can be submitted.");
        }

        return $this->eventRepository->update($event, ['status' => eventStatus::Submitted]);
    }

    /**
     * Mark an event as under review.
     * State transition: Submitted -> Under Review
     *
     * @param int $eventId
     * @return bool
     * @throws Exception
     */
    public function review(int $eventId): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        if ($event->status !== eventStatus::Submitted) {
            throw new Exception("event is not submitted for review.");
        }

        return $this->eventRepository->update($event, ['status' => eventStatus::UnderReview]);
    }

    /**
     * Approve an event.
     * State transition: Under Review -> Approved
     *
     * @param int $eventId
     * @param int $adminId
     * @return bool
     * @throws Exception
     */
    public function approve(int $eventId, int $adminId): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        if ($event->status !== eventStatus::UnderReview) {
            throw new Exception("Only events under review can be approved.");
        }

        return $this->eventRepository->update($event, [
            'status' => eventStatus::Approved,
            'approved_by' => $adminId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Request a revision for the event.
     * State transition: Under Review -> Revision Required
     *
     * @param int $eventId
     * @return bool
     * @throws Exception
     */
    public function requestRevision(int $eventId): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        if ($event->status !== eventStatus::UnderReview) {
            throw new Exception("Only events under review can be flagged for revision.");
        }

        return $this->eventRepository->update($event, ['status' => eventStatus::RevisionRequired]);
    }

    /**
     * Publish an event.
     * State transition: Approved -> Published
     * Applies critical business rules for publication.
     *
     * @param int $eventId
     * @param int $publisherId
     * @return bool
     * @throws Exception
     */
    public function publish(int $eventId, int $publisherId): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        if ($event->status !== eventStatus::Approved) {
            throw new Exception("event must be approved before publication.");
        }

        // Business Rule 1: Organizer must be Approved
        $organizer = $this->organizerRepository->findById($event->organizer_profile_id);
        if (!$organizer || $organizer->status !== OrganizerStatus::Approved) {
            throw new Exception("Cannot publish: Organizer profile must be approved.");
        }

        // Business Rule 2: Organizer must have an Approved SPK
        $spks = $this->spkRepository->getByOrganizerProfile($event->organizer_profile_id);
        $hasApprovedSpk = false;
        foreach ($spks as $spk) {
            if ($spk->status === SpkStatus::Approved) {
                $hasApprovedSpk = true;
                break;
            }
        }
        if (!$hasApprovedSpk) {
            throw new Exception("Cannot publish: Organizer must have an approved SPK.");
        }

        // Business Rule 3: event must have at least one schedule
        $schedules = $this->scheduleRepository->getByevent($eventId);
        if ($schedules->isEmpty()) {
            throw new Exception("Cannot publish: event must have at least one active schedule.");
        }

        return $this->eventRepository->update($event, [
            'status' => eventStatus::Published,
            'published_by' => $publisherId,
            'published_at' => now(),
        ]);
    }

    /**
     * Mark event as ongoing.
     * State transition: Published -> Ongoing
     *
     * @param int $eventId
     * @return bool
     * @throws Exception
     */
    public function markAsOngoing(int $eventId): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        if ($event->status !== eventStatus::Published) {
            throw new Exception("event must be published before it can be marked as ongoing.");
        }

        return $this->eventRepository->update($event, ['status' => eventStatus::Ongoing]);
    }

    /**
     * Mark event as finished.
     * State transition: Ongoing -> Finished
     *
     * @param int $eventId
     * @return bool
     * @throws Exception
     */
    public function markAsFinished(int $eventId): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        if ($event->status !== eventStatus::Ongoing) {
            throw new Exception("Only ongoing events can be marked as finished.");
        }

        return $this->eventRepository->update($event, ['status' => eventStatus::Finished]);
    }

    /**
     * Archive an event.
     * State transition: Finished -> Archived
     *
     * @param int $eventId
     * @return bool
     * @throws Exception
     */
    public function archive(int $eventId): bool
    {
        $event = $this->eventRepository->findOrFail($eventId);

        if ($event->status !== eventStatus::Finished) {
            throw new Exception("Only finished events can be archived.");
        }

        return $this->eventRepository->update($event, ['status' => eventStatus::Archived]);
    }
}
