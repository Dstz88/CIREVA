<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Notification;

interface NotificationRepositoryInterface
{
    /**
     * Get all notifications.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated notifications.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a notification by ID.
     *
     * @param int $id
     * @return Notification|null
     */
    public function findById(int $id): ?Notification;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Notification
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Notification;

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return Notification
     */
    public function create(array $data): Notification;

    /**
     * Update an existing notification.
     *
     * @param Notification $notification
     * @param array $data
     * @return bool
     */
    public function update(Notification $notification, array $data): bool;

    /**
     * Delete a notification.
     *
     * @param Notification $notification
     * @return bool|null
     */
    public function delete(Notification $notification): ?bool;

    /**
     * Find paginated notifications by user ID.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get unread notifications for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getUnreadByUser(int $userId): Collection;
}

