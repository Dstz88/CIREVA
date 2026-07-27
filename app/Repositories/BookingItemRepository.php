<?php

namespace App\Repositories;

use App\Repositories\Contracts\BookingItemRepositoryInterface;

use App\Models\BookingItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingItemRepository implements BookingItemRepositoryInterface
{
    /**
     * Get all booking items.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return BookingItem::all();
    }

    /**
     * Get paginated booking items.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return BookingItem::paginate($perPage);
    }

    /**
     * Find a booking item by ID.
     *
     * @param int $id
     * @return BookingItem|null
     */
    public function findById(int $id): ?BookingItem
    {
        return BookingItem::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return BookingItem
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): BookingItem
    {
        return BookingItem::findOrFail($id);
    }

    /**
     * Create a new booking item.
     *
     * @param array $data
     * @return BookingItem
     */
    public function create(array $data): BookingItem
    {
        return BookingItem::create($data);
    }

    /**
     * Update an existing booking item.
     *
     * @param BookingItem $bookingItem
     * @param array $data
     * @return bool
     */
    public function update(BookingItem $bookingItem, array $data): bool
    {
        return $bookingItem->update($data);
    }

    /**
     * Delete a booking item.
     *
     * @param BookingItem $bookingItem
     * @return bool|null
     */
    public function delete(BookingItem $bookingItem): ?bool
    {
        return $bookingItem->delete();
    }

    /**
     * Get booking items by booking ID.
     *
     * @param int $bookingId
     * @return Collection
     */
    public function getByBooking(int $bookingId): Collection
    {
        return BookingItem::where('booking_id', $bookingId)->get();
    }

    /**
     * Check if any bookings exist for a given event ID.
     *
     * @param int $eventId
     * @return bool
     */
    public function existsForevent(int $eventId): bool
    {
        return BookingItem::whereHas('ticket', function ($query) use ($eventId) {
            $query->where('event_id', $eventId);
        })->exists();
    }
}
