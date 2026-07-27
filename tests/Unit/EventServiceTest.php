<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\eventService;
use App\Models\event;
use App\Models\User;
use App\Models\OrganizerProfile;
use App\Models\eventCategory;
use App\Models\eventLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\eventStatus;
use Illuminate\Support\Facades\Storage;

class eventServiceTest extends TestCase
{
    use RefreshDatabase;

    protected eventService $eventService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->eventService = app(eventService::class);
        Storage::fake('public');
    }

    public function test_can_create_draft_event()
    {
        $organizer = User::factory()->create();
        $profile = OrganizerProfile::factory()->create(['user_id' => $organizer->id]);
        $category = eventCategory::factory()->create();
        $location = eventLocation::factory()->create();

        $data = [
            'title' => 'Test event',
            'description' => 'Description of test event',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'banner' => 'images/test-banner.jpg',
        ];

        $event = $this->eventService->createDraft($profile->id, $data);

        $this->assertInstanceOf(event::class, $event);
        $this->assertEquals('Test event', $event->title);
        $this->assertEquals(eventStatus::Draft, $event->status);
    }

    public function test_state_machine_draft_to_submitted()
    {
        $event = event::factory()->create(['status' => eventStatus::Draft->value]);

        $result = $this->eventService->submit($event->id);
        $this->assertTrue($result);

        $event->refresh();
        $this->assertEquals(eventStatus::Submitted, $event->status);
    }

    public function test_state_machine_submitted_to_under_review()
    {
        $event = event::factory()->create(['status' => eventStatus::Submitted->value]);

        $result = $this->eventService->review($event->id);
        $this->assertTrue($result);

        $event->refresh();
        $this->assertEquals(eventStatus::UnderReview, $event->status);
    }

    public function test_state_machine_under_review_to_approved()
    {
        $admin = User::factory()->create();
        $event = event::factory()->create(['status' => eventStatus::UnderReview->value]);

        $result = $this->eventService->approve($event->id, $admin->id);
        $this->assertTrue($result);

        $event->refresh();
        $this->assertEquals(eventStatus::Approved, $event->status);
    }

    public function test_state_machine_under_review_to_revision_required()
    {
        $event = event::factory()->create(['status' => eventStatus::UnderReview->value]);

        $result = $this->eventService->requestRevision($event->id);
        $this->assertTrue($result);

        $event->refresh();
        $this->assertEquals(eventStatus::RevisionRequired, $event->status);
    }
}
