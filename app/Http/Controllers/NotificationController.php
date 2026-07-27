<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Exception;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display listing of notifications for authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $notifications,
                'unread_count' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            ]);
        }

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark single notification as read.
     */
    public function markAsRead(Notification $notification, Request $request)
    {
        if ((int)$notification->user_id !== (int)Auth::id()) {
            abort(403, 'Unauthorized notification access.');
        }

        try {
            $this->notificationService->markAsRead($notification->id);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Notification marked as read.']);
            }

            return back()->with('success', 'Notifikasi telah ditandai dibaca.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Mark all notifications as read for current user.
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $count = $this->notificationService->markAllAsRead(Auth::id());

            if ($request->wantsJson()) {
                return response()->json(['message' => "{$count} notifications marked as read."]);
            }

            return back()->with('success', "Semua notifikasi ({$count}) berhasil ditandai dibaca.");
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
