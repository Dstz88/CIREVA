<?php

namespace App\Services;

use App\Repositories\Contracts\NotificationRepositoryInterface;
use App\Models\Notification;
use Exception;

class NotificationService
{
    protected NotificationRepositoryInterface $notificationRepository;

    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Send a general notification to a user.
     *
     * @param int $userId
     * @param string $title
     * @param string $message
     * @param string|null $relatedType
     * @param int|null $relatedId
     * @return Notification
     */
    public function sendNotification(
        int $userId,
        string $title,
        string $message,
        ?string $relatedType = null,
        ?int $relatedId = null
    ): Notification {
        return $this->notificationRepository->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
        ]);
    }

    /**
     * Mark a specific notification as read.
     *
     * @param int $notificationId
     * @return bool
     * @throws Exception
     */
    public function markAsRead(int $notificationId): bool
    {
        $notification = $this->notificationRepository->findOrFail($notificationId);

        if ($notification->is_read) {
            return true; // Already read
        }

        return $this->notificationRepository->update($notification, ['is_read' => true]);
    }

    /**
     * Mark all unread notifications for a user as read.
     *
     * @param int $userId
     * @return int Number of notifications marked as read
     */
    public function markAllAsRead(int $userId): int
    {
        $unreadNotifications = $this->notificationRepository->getUnreadByUser($userId);
        $count = 0;

        foreach ($unreadNotifications as $notification) {
            if ($this->notificationRepository->update($notification, ['is_read' => true])) {
                $count++;
            }
        }

        return $count;
    }

    // =========================================================================
    // Specific Trigger Helpers based on 05_WORKFLOW.md (Notification Workflow)
    // =========================================================================

    public function notifyOrganizerApproved(int $userId, int $organizerId): Notification
    {
        return $this->sendNotification(
            $userId,
            "Profil Organizer Disetujui",
            "Selamat! Profil organizer Anda telah disetujui oleh admin. Anda sekarang dapat melanjutkan proses aktivasi.",
            "organizer",
            $organizerId
        );
    }

    public function notifySpkApproved(int $userId, int $spkId): Notification
    {
        return $this->sendNotification(
            $userId,
            "SPK Disetujui",
            "Dokumen Surat Perjanjian Kerjasama (SPK) Anda telah disetujui. Organizer Anda kini aktif.",
            "spk",
            $spkId
        );
    }

    public function notifyeventApproved(int $userId, int $eventId, string $eventTitle): Notification
    {
        return $this->sendNotification(
            $userId,
            "event Disetujui",
            "event '{$eventTitle}' telah disetujui oleh admin dan siap dipublikasikan.",
            "event",
            $eventId
        );
    }

    public function notifyBookingCreated(int $userId, int $bookingId, string $bookingCode): Notification
    {
        return $this->sendNotification(
            $userId,
            "Booking Berhasil Dibuat",
            "Booking dengan kode {$bookingCode} berhasil dibuat. Silakan selesaikan pembayaran Anda.",
            "booking",
            $bookingId
        );
    }

    public function notifyPaymentSuccess(int $userId, int $bookingId, string $bookingCode): Notification
    {
        return $this->sendNotification(
            $userId,
            "Pembayaran Berhasil",
            "Pembayaran untuk booking {$bookingCode} telah berhasil diverifikasi. E-Ticket Anda siap diunduh.",
            "booking",
            $bookingId
        );
    }

    public function notifyeventReminder(int $userId, int $eventId, string $eventTitle): Notification
    {
        return $this->sendNotification(
            $userId,
            "Pengingat event",
            "event '{$eventTitle}' akan segera dimulai. Jangan lupa untuk hadir tepat waktu!",
            "event",
            $eventId
        );
    }

    public function notifyeventCancelled(int $userId, int $eventId, string $eventTitle): Notification
    {
        return $this->sendNotification(
            $userId,
            "event Dibatalkan",
            "Mohon maaf, event '{$eventTitle}' telah dibatalkan. Silakan cek detail pada sistem untuk informasi lebih lanjut mengenai kompensasi atau pengembalian dana.",
            "event",
            $eventId
        );
    }
}
