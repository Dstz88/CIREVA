<?php

namespace App\Services;

use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\Contracts\eventRepositoryInterface;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use App\Enums\eventStatus;
use Exception;
use InvalidArgumentException;

class TicketService
{
    protected TicketRepositoryInterface $ticketRepository;
    protected eventRepositoryInterface $eventRepository;

    public function __construct(TicketRepositoryInterface $ticketRepository, eventRepositoryInterface $eventRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->eventRepository = $eventRepository;
    }

    /**
     * Create a new ticket for an event.
     * State transition: Null -> Inactive
     *
     * @param int $eventId
     * @param array $data
     * @return Ticket
     * @throws InvalidArgumentException
     */
    public function createTicket(int $eventId, array $data): Ticket
    {
        if (isset($data['quota']) && (int)$data['quota'] < 0) {
            throw new InvalidArgumentException("Kuota tiket tidak boleh bernilai negatif.");
        }

        $data['event_id'] = $eventId;
        $data['status'] = TicketStatus::Inactive;
        $data['sold'] = 0;

        return $this->ticketRepository->create($data);
    }

    /**
     * Update existing ticket details.
     *
     * @param int $ticketId
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function updateTicket(int $ticketId, array $data): bool
    {
        $ticket = $this->ticketRepository->findOrFail($ticketId);

        if (isset($data['quota'])) {
            $quota = (int) $data['quota'];
            if ($quota < 0) {
                throw new InvalidArgumentException("Kuota tiket tidak boleh bernilai negatif.");
            }
            if ($quota < $ticket->sold) {
                throw new InvalidArgumentException("Kuota tidak boleh diatur lebih kecil dari jumlah tiket yang sudah terjual.");
            }
        }

        return $this->ticketRepository->update($ticket, $data);
    }

    /**
     * Activate a ticket for sale.
     * State transition: Inactive -> Active
     *
     * @param int $ticketId
     * @return bool
     * @throws Exception
     */
    public function activateTicket(int $ticketId): bool
    {
        $ticket = $this->ticketRepository->findOrFail($ticketId);

        $event = $this->eventRepository->findById($ticket->event_id);

        // Business Rule: Tiket hanya aktif jika event Published
        if (!$event || $event->status !== eventStatus::Published) {
            throw new Exception("Tiket hanya dapat diaktifkan jika event berstatus Published.");
        }

        if ($ticket->quota <= $ticket->sold) {
            throw new Exception("Tidak dapat mengaktifkan tiket yang kuotanya sudah habis terjual.");
        }

        return $this->ticketRepository->update($ticket, ['status' => TicketStatus::Active]);
    }

    /**
     * Deactivate a ticket (stop sales).
     * State transition: Active / Sold Out -> Inactive
     *
     * @param int $ticketId
     * @return bool
     * @throws Exception
     */
    public function deactivateTicket(int $ticketId): bool
    {
        $ticket = $this->ticketRepository->findOrFail($ticketId);

        return $this->ticketRepository->update($ticket, ['status' => TicketStatus::Inactive]);
    }

    /**
     * Process a ticket sale, increment sold count, and check quota limits.
     * State transition (conditional): Active -> Sold Out
     *
     * @param int $ticketId
     * @param int $quantity
     * @return bool
     * @throws Exception
     */
    public function processSale(int $ticketId, int $quantity): bool
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Kuantitas penjualan tiket harus lebih dari 0.");
        }

        $ticket = $this->ticketRepository->findOrFail($ticketId);

        if ($ticket->status !== TicketStatus::Active) {
            throw new Exception("Tidak dapat menjual tiket yang tidak aktif atau sudah terjual habis.");
        }

        $newSold = $ticket->sold + $quantity;

        if ($newSold > $ticket->quota) {
            throw new Exception("Kuota tiket tidak mencukupi untuk jumlah pembelian ini.");
        }

        $data = ['sold' => $newSold];

        if ($newSold === $ticket->quota) {
            $data['status'] = TicketStatus::SoldOut;
        }

        return $this->ticketRepository->update($ticket, $data);
    }

    /**
     * Reverse a ticket sale (e.g., when a booking is cancelled or expires).
     * State transition (conditional): Sold Out -> Active
     *
     * @param int $ticketId
     * @param int $quantity
     * @return bool
     * @throws Exception
     */
    public function cancelSale(int $ticketId, int $quantity): bool
    {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Kuantitas pembatalan tiket harus lebih dari 0.");
        }

        $ticket = $this->ticketRepository->findOrFail($ticketId);

        $newSold = $ticket->sold - $quantity;

        if ($newSold < 0) {
            $newSold = 0; // Prevent negative sold count just in case
        }

        $data = ['sold' => $newSold];

        // If it was sold out, it might become active again
        if ($ticket->status === TicketStatus::SoldOut && $newSold < $ticket->quota) {
            $data['status'] = TicketStatus::Active;
        }

        return $this->ticketRepository->update($ticket, $data);
    }
}
