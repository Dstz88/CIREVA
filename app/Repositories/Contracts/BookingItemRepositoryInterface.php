<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\BookingItem;

interface BookingItemRepositoryInterface
{
    /**
     * Get all booking items.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated booking items.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a booking item by ID.
     *
     * @param int $id
     * @return BookingItem|null
     */
    public function findById(int $id): ?BookingItem;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return BookingItem
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): BookingItem;

    /**
     * Create a new booking item.
     *
     * @param array $data
     * @return BookingItem
     */
    public function create(array $data): BookingItem;

    /**
     * Update an existing booking item.
     *
     * @param BookingItem $bookingItem
     * @param array $data
     * @return bool
     */
    public function update(BookingItem $bookingItem, array $data): bool;

    /**
     * Delete a booking item.
     *
     * @param BookingItem $bookingItem
     * @return bool|null
     */
    public function delete(BookingItem $bookingItem): ?bool;

    /**
     * Get booking items by booking ID.
     *
     * @param int $bookingId
     * @return Collection
     */
    public function getByBooking(int $bookingId): Collection;

    /**
     * Check if any bookings exist for a given event ID.
     *
     * @param int $eventId
     * @return bool
     */
    public function existsForevent(int $eventId): bool;
}
