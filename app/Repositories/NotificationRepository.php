<?php

namespace App\Repositories;

use App\Repositories\Contracts\NotificationRepositoryInterface;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    /**
     * Get all notifications.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Notification::all();
    }

    /**
     * Get paginated notifications.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Notification::paginate($perPage);
    }

    /**
     * Find a notification by ID.
     *
     * @param int $id
     * @return Notification|null
     */
    public function findById(int $id): ?Notification
    {
        return Notification::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Notification
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Notification
    {
        return Notification::findOrFail($id);
    }

    /**
     * Create a new notification.
     *
     * @param array $data
     * @return Notification
     */
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    /**
     * Update an existing notification.
     *
     * @param Notification $notification
     * @param array $data
     * @return bool
     */
    public function update(Notification $notification, array $data): bool
    {
        return $notification->update($data);
    }

    /**
     * Delete a notification.
     *
     * @param Notification $notification
     * @return bool|null
     */
    public function delete(Notification $notification): ?bool
    {
        return $notification->delete();
    }

    /**
     * Find paginated notifications by user ID.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Notification::where('user_id', $userId)
            ->latest('created_at')
            ->paginate($perPage);
    }

    /**
     * Get unread notifications for a user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getUnreadByUser(int $userId): Collection
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->latest('created_at')
            ->get();
    }
}

