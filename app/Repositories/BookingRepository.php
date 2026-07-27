<?php

namespace App\Repositories;

use App\Repositories\Contracts\BookingRepositoryInterface;

use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingRepository implements BookingRepositoryInterface
{
    /**
     * Get all bookings.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Booking::all();
    }

    /**
     * Get paginated bookings.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Booking::paginate($perPage);
    }

    /**
     * Find a booking by ID.
     *
     * @param int $id
     * @return Booking|null
     */
    public function findById(int $id): ?Booking
    {
        return Booking::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Booking
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Booking
    {
        return Booking::findOrFail($id);
    }

    /**
     * Create a new booking.
     *
     * @param array $data
     * @return Booking
     */
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    /**
     * Update an existing booking.
     *
     * @param Booking $booking
     * @param array $data
     * @return bool
     */
    public function update(Booking $booking, array $data): bool
    {
        return $booking->update($data);
    }

    /**
     * Delete a booking.
     *
     * @param Booking $booking
     * @return bool|null
     */
    public function delete(Booking $booking): ?bool
    {
        return $booking->delete();
    }

    /**
     * Find paginated bookings by user ID.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Booking::with(['items.ticket.event', 'transaction'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find paginated bookings by user ID and status filter.
     *
     * @param int $userId
     * @param string|array $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUserAndStatus(int $userId, $status, int $perPage = 15): LengthAwarePaginator
    {
        $query = Booking::with(['items.ticket.event', 'transaction'])
            ->where('user_id', $userId);

        if (is_array($status)) {
            $query->whereIn('status', $status);
        } else {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find paginated bookings by status.
     *
     * @param BookingStatus $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByStatus(BookingStatus $status, int $perPage = 15): LengthAwarePaginator
    {
        return Booking::with(['items.ticket.event', 'transaction'])
            ->where('status', $status)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find paginated bookings containing tickets for an organizer profile.
     *
     * @param int $organizerProfileId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByOrganizerProfile(int $organizerProfileId, int $perPage = 15): LengthAwarePaginator
    {
        return Booking::with(['items.ticket.event', 'transaction'])
            ->whereHas('items.ticket.event', function ($query) use ($organizerProfileId) {
                $query->where('organizer_profile_id', $organizerProfileId);
            })
            ->latest()
            ->paginate($perPage);
    }
}
