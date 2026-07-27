<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Ticket;

interface TicketRepositoryInterface
{
    /**
     * Get all tickets.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated tickets.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a ticket by ID.
     *
     * @param int $id
     * @return Ticket|null
     */
    public function findById(int $id): ?Ticket;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Ticket
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Ticket;

    /**
     * Create a new ticket.
     *
     * @param array $data
     * @return Ticket
     */
    public function create(array $data): Ticket;

    /**
     * Update an existing ticket.
     *
     * @param Ticket $ticket
     * @param array $data
     * @return bool
     */
    public function update(Ticket $ticket, array $data): bool;

    /**
     * Delete a ticket.
     *
     * @param Ticket $ticket
     * @return bool|null
     */
    public function delete(Ticket $ticket): ?bool;

    /**
     * Get active tickets for a specific event.
     *
     * @param int $eventId
     * @return Collection
     */
    public function getActive(int $eventId): Collection;

    /**
     * Get available tickets for a specific event.
     *
     * @param int $eventId
     * @return Collection
     */
    public function getAvailable(int $eventId): Collection;
}
