<?php

namespace App\Repositories;

use App\Repositories\Contracts\TicketRepositoryInterface;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TicketRepository implements TicketRepositoryInterface
{
    /**
     * Get all tickets.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Ticket::all();
    }

    /**
     * Get paginated tickets.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Ticket::paginate($perPage);
    }

    /**
     * Find a ticket by ID.
     *
     * @param int $id
     * @return Ticket|null
     */
    public function findById(int $id): ?Ticket
    {
        return Ticket::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Ticket
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Ticket
    {
        return Ticket::findOrFail($id);
    }

    /**
     * Create a new ticket.
     *
     * @param array $data
     * @return Ticket
     */
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    /**
     * Update an existing ticket.
     *
     * @param Ticket $ticket
     * @param array $data
     * @return bool
     */
    public function update(Ticket $ticket, array $data): bool
    {
        return $ticket->update($data);
    }

    /**
     * Delete a ticket.
     *
     * @param Ticket $ticket
     * @return bool|null
     */
    public function delete(Ticket $ticket): ?bool
    {
        return $ticket->delete();
    }

    /**
     * Get active tickets for a specific event.
     *
     * @param int $eventId
     * @return Collection
     */
    public function getActive(int $eventId): Collection
    {
        return Ticket::where('event_id', $eventId)
            ->active()
            ->get();
    }

    /**
     * Get available tickets for a specific event.
     *
     * @param int $eventId
     * @return Collection
     */
    public function getAvailable(int $eventId): Collection
    {
        return Ticket::where('event_id', $eventId)
            ->available()
            ->get();
    }
}
