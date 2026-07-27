<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Booking;
use App\Enums\BookingStatus;

interface BookingRepositoryInterface
{
    /**
     * Get all bookings.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated bookings.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a booking by ID.
     *
     * @param int $id
     * @return Booking|null
     */
    public function findById(int $id): ?Booking;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Booking
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Booking;

    /**
     * Create a new booking.
     *
     * @param array $data
     * @return Booking
     */
    public function create(array $data): Booking;

    /**
     * Update an existing booking.
     *
     * @param Booking $booking
     * @param array $data
     * @return bool
     */
    public function update(Booking $booking, array $data): bool;

    /**
     * Delete a booking.
     *
     * @param Booking $booking
     * @return bool|null
     */
    public function delete(Booking $booking): ?bool;

    /**
     * Find paginated bookings by user ID.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find paginated bookings by user ID and status.
     *
     * @param int $userId
     * @param string|array $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUserAndStatus(int $userId, $status, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find paginated bookings by status.
     *
     * @param BookingStatus $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByStatus(BookingStatus $status, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find paginated bookings containing tickets for an organizer profile.
     *
     * @param int $organizerProfileId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByOrganizerProfile(int $organizerProfileId, int $perPage = 15): LengthAwarePaginator;
}

