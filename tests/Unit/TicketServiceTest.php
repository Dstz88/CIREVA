<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\TicketService;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class TicketServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_activate_sets_status_to_active()
    {
        // Setup mock repository
        $mockRepo = Mockery::mock(TicketRepositoryInterface::class);
        $ticket = new Ticket(['id' => 1, 'event_id' => 1, 'status' => \App\Enums\TicketStatus::Inactive, 'quota' => 10, 'sold' => 0]);

        $mockRepo->shouldReceive('findOrFail')->with(1)->once()->andReturn($ticket);
        $mockRepo->shouldReceive('update')->once()->andReturn(true);

        // Bind mock
        $this->app->instance(TicketRepositoryInterface::class, $mockRepo);

        // Execute service
        $service = $this->app->make(TicketService::class);

        // Mock event repo to return published event
        $mockeventRepo = Mockery::mock(\App\Repositories\Contracts\eventRepositoryInterface::class);
        $event = \App\Models\event::factory()->create(['status' => \App\Enums\eventStatus::Published]);
        $mockeventRepo->shouldReceive('findOrFail')->with(1)->andReturn($event);
        $this->app->instance(\App\Repositories\Contracts\eventRepositoryInterface::class, $mockeventRepo);

        $result = $service->activateTicket(1);

        $this->assertTrue($result);
    }

    public function test_deactivate_sets_status_to_inactive()
    {
        $mockRepo = Mockery::mock(TicketRepositoryInterface::class);
        $ticket = new Ticket(['id' => 1, 'status' => \App\Enums\TicketStatus::Active]);

        $mockRepo->shouldReceive('findOrFail')->with(1)->once()->andReturn($ticket);
        $mockRepo->shouldReceive('update')->once()->andReturn(true);

        $this->app->instance(TicketRepositoryInterface::class, $mockRepo);

        $mockeventRepo = Mockery::mock(\App\Repositories\Contracts\eventRepositoryInterface::class);
        $this->app->instance(\App\Repositories\Contracts\eventRepositoryInterface::class, $mockeventRepo);

        $service = $this->app->make(TicketService::class);
        $result = $service->deactivateTicket(1);

        $this->assertTrue($result);
    }
}
