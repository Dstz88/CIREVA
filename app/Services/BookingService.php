<?php

namespace App\Services;

use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\BookingItemRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Models\Booking;
use App\Enums\BookingStatus;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingService
{
    protected BookingRepositoryInterface $bookingRepository;
    protected BookingItemRepositoryInterface $bookingItemRepository;
    protected TicketRepositoryInterface $ticketRepository;
    protected TicketService $ticketService;

    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        BookingItemRepositoryInterface $bookingItemRepository,
        TicketRepositoryInterface $ticketRepository,
        TicketService $ticketService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookingItemRepository = $bookingItemRepository;
        $this->ticketRepository = $ticketRepository;
        $this->ticketService = $ticketService;
    }

    /**
     * Create a new booking with its items.
     * State transition: Null -> Pending
     *
     * @param int $userId
     * @param array $items Array of ['ticket_id' => x, 'quantity' => y]
     * @return Booking
     * @throws Exception
     */
    public function createBooking(int $userId, array $items): Booking
    {
        if (empty($items)) {
            throw new InvalidArgumentException("Booking must contain at least one item.");
        }

        return DB::transaction(function () use ($userId, $items) {
            $totalAmount = 0;
            $bookingItemsData = [];

            foreach ($items as $item) {
                if (!isset($item['ticket_id']) || !isset($item['quantity']) || $item['quantity'] <= 0) {
                    throw new InvalidArgumentException("Invalid ticket or quantity.");
                }

                $ticketId = $item['ticket_id'];
                $quantity = $item['quantity'];

                $ticket = $this->ticketRepository->findOrFail($ticketId);

                // Call TicketService to process sale and validate quota/status
                $this->ticketService->processSale($ticketId, $quantity);

                $price = $ticket->price;
                $subtotal = $price * $quantity;
                $totalAmount += $subtotal;

                $bookingItemsData[] = [
                    'ticket_id' => $ticketId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ];
            }

            // Create Booking
            $bookingCode = strtoupper(Str::random(10));
            $booking = $this->bookingRepository->create([
                'user_id' => $userId,
                'booking_code' => $bookingCode,
                'total_amount' => $totalAmount,
                'status' => BookingStatus::Pending,
                'expired_at' => now()->addHours(24) // Set expiration for 24 hours
            ]);

            // Create Booking Items
            foreach ($bookingItemsData as $itemData) {
                $itemData['booking_id'] = $booking->id;
                $this->bookingItemRepository->create($itemData);
            }

            return $booking;
        });
    }

    /**
     * Mark booking as Paid.
     * State transition: Pending -> Paid
     *
     * @param int $bookingId
     * @return bool
     * @throws Exception
     */
    public function markAsPaid(int $bookingId): bool
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);

        if ($booking->status !== BookingStatus::Pending) {
            throw new Exception("Only pending bookings can be marked as paid.");
        }

        return $this->bookingRepository->update($booking, ['status' => BookingStatus::PaymentCompleted]);
    }

    /**
     * Cancel a booking and restore ticket quotas.
     * State transition: Pending -> Cancelled
     *
     * @param int $bookingId
     * @return bool
     * @throws Exception
     */
    public function cancelBooking(int $bookingId): bool
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);

        if ($booking->status !== BookingStatus::Pending) {
            throw new Exception("Only pending bookings can be cancelled.");
        }

        return $this->restoreTicketsAndUpdateStatus($booking, BookingStatus::Cancelled);
    }

    /**
     * Complete a booking (e.g., event finished or tickets used).
     * State transition: Paid -> Completed
     *
     * @param int $bookingId
     * @return bool
     * @throws Exception
     */
    public function completeBooking(int $bookingId): bool
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);

        if ($booking->status !== BookingStatus::PaymentCompleted) {
            throw new Exception("Only paid bookings can be marked as completed.");
        }

        return $this->bookingRepository->update($booking, ['status' => BookingStatus::Confirmed]);
    }

    /**
     * Expire a pending booking (e.g., payment timeout) and restore quotas.
     * State transition: Pending -> Expired
     *
     * @param int $bookingId
     * @return bool
     * @throws Exception
     */
    public function expireBooking(int $bookingId): bool
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);

        if ($booking->status !== BookingStatus::Pending) {
            throw new Exception("Only pending bookings can be marked as expired.");
        }

        return $this->restoreTicketsAndUpdateStatus($booking, BookingStatus::Expired);
    }

    /**
     * Helper to restore tickets and update booking status.
     * 
     * @param Booking $booking
     * @param BookingStatus $status
     * @return bool
     */
    private function restoreTicketsAndUpdateStatus(Booking $booking, BookingStatus $status): bool
    {
        return DB::transaction(function () use ($booking, $status) {
            // Restore ticket sales
            $items = $this->bookingItemRepository->getByBooking($booking->id);
            foreach ($items as $item) {
                $this->ticketService->cancelSale($item->ticket_id, $item->quantity);
            }

            return $this->bookingRepository->update($booking, ['status' => $status]);
        });
    }
}
