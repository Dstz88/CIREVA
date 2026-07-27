<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BookingService;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Exception;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_booking_fails_if_stock_insufficient()
    {
        $ticketRepo = Mockery::mock(TicketRepositoryInterface::class);
        $bookingRepo = Mockery::mock(BookingRepositoryInterface::class);
        $bookingItemRepo = Mockery::mock(\App\Repositories\Contracts\BookingItemRepositoryInterface::class);
        $ticketService = Mockery::mock(\App\Services\TicketService::class);

        $ticket = new Ticket(['id' => 1, 'quantity' => 1, 'price' => 100]);
        $ticketRepo->shouldReceive('findOrFail')->with(1)->andReturn($ticket);
        $ticketService->shouldReceive('processSale')->andThrow(new Exception('Kuota tiket tidak mencukupi untuk jumlah pembelian ini.'));

        $this->app->instance(TicketRepositoryInterface::class, $ticketRepo);
        $this->app->instance(BookingRepositoryInterface::class, $bookingRepo);
        $this->app->instance(\App\Repositories\Contracts\BookingItemRepositoryInterface::class, $bookingItemRepo);
        $this->app->instance(\App\Services\TicketService::class, $ticketService);

        $service = $this->app->make(BookingService::class);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Kuota tiket tidak mencukupi untuk jumlah pembelian ini.');

        // Format is items array
        $service->createBooking(1, [['ticket_id' => 1, 'quantity' => 2]]);
    }

    public function test_create_booking_deducts_stock_and_creates_record()
    {
        $ticketRepo = Mockery::mock(TicketRepositoryInterface::class);
        $bookingRepo = Mockery::mock(BookingRepositoryInterface::class);
        $bookingItemRepo = Mockery::mock(\App\Repositories\Contracts\BookingItemRepositoryInterface::class);
        $ticketService = Mockery::mock(\App\Services\TicketService::class);

        $ticket = new Ticket(['id' => 1, 'quantity' => 5, 'price' => 100, 'status' => \App\Enums\TicketStatus::Active]);
        $ticketRepo->shouldReceive('findOrFail')->with(1)->andReturn($ticket);
        
        $ticketService->shouldReceive('processSale')->with(1, 2)->once()->andReturn(true);
        
        $booking = new Booking(['id' => 1]);
        $bookingRepo->shouldReceive('create')->once()->andReturn($booking);
        $bookingItem = new \App\Models\BookingItem(['id' => 1]);
        $bookingItemRepo->shouldReceive('create')->once()->andReturn($bookingItem);

        $this->app->instance(TicketRepositoryInterface::class, $ticketRepo);
        $this->app->instance(BookingRepositoryInterface::class, $bookingRepo);
        $this->app->instance(\App\Repositories\Contracts\BookingItemRepositoryInterface::class, $bookingItemRepo);
        $this->app->instance(\App\Services\TicketService::class, $ticketService);

        $service = $this->app->make(BookingService::class);
        
        $result = $service->createBooking(1, [['ticket_id' => 1, 'quantity' => 2]]);
        $this->assertInstanceOf(Booking::class, $result);
    }
}

