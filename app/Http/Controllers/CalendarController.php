<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;
use App\Models\eventSchedule;
use App\Http\Requests\StoreCalendarRequest;
use App\Http\Requests\UpdateCalendarRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Exception;

class CalendarController extends Controller
{
    use AuthorizesRequests;

    protected CalendarService $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $userRoleName = is_object($user?->role) ? strtolower($user->role->name ?? '') : strtolower((string)($user?->role ?? ''));
        if ($user && $userRoleName === 'admin') {
            $now = now();
            $cutoff = now()->subDays(7);

            // Upcoming events (start_datetime >= now) and created within 7 days
            $upcomingevents = \App\Models\event::with(['category', 'location', 'organizerProfile.user', 'schedules'])
                ->whereIn('status', ['published', 'approved'])
                ->where('created_at', '>=', $cutoff)
                ->whereHas('schedules', function ($q) use ($now) {
                    $q->where('start_datetime', '>=', $now);
                })
                ->latest()
                ->get();

            // Past events (end_datetime < now) and created within 7 days
            $pastevents = \App\Models\event::with(['category', 'location', 'organizerProfile.user', 'schedules'])
                ->whereIn('status', ['published', 'approved'])
                ->where('created_at', '>=', $cutoff)
                ->whereHas('schedules', function ($q) use ($now) {
                    $q->where('end_datetime', '<', $now);
                })
                ->latest()
                ->get();

            return view('admin.calendars.index', compact('upcomingevents', 'pastevents'));
        }

        $schedules = eventSchedule::with(['event.category', 'event.location', 'event.tickets'])->paginate(30);
        $events = \App\Models\event::with(['category', 'location', 'tickets', 'schedules'])
            ->whereIn('status', ['published', 'approved'])
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Calendar list endpoint.',
                'data' => $schedules,
                'events' => $events
            ]);
        }

        $roleName = is_object($user?->role) ? strtolower($user->role->name ?? '') : strtolower((string)($user?->role ?? ''));
        if ($roleName === 'organizer') {
            return view('organizer.calendar.index', compact('schedules', 'events'));
        }

        return view('calendar.index', compact('schedules', 'events'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Calendar create endpoint.']);
        }
        return view('organizer.calendar.create');
    }

    public function store(StoreCalendarRequest $request)
    {
        try {
            $validated = $request->validated();
            $schedule = $this->calendarService->createSchedule((int)$validated['event_id'], $validated);
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Calendar schedule created successfully.',
                    'data' => $schedule
                ], 201);
            }
            return redirect()->route('organizer.calendar.index')->with('success', 'Schedule created successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit(eventSchedule $calendar, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Calendar edit endpoint.']);
        }
        return view('organizer.calendar.edit', compact('calendar'));
    }

    public function update(UpdateCalendarRequest $request, eventSchedule $calendar)
    {
        $this->authorize('update', $calendar);
        try {
            $isAdmin = Auth::user() && Auth::user()->role && strtolower((string)Auth::user()->role->name) === 'admin';
            $this->calendarService->updateSchedule($calendar->id, $request->validated(), $isAdmin);
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Calendar schedule updated successfully.']);
            }
            return redirect()->route('organizer.calendar.index')->with('success', 'Schedule updated successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(eventSchedule $calendar, Request $request)
    {
        $this->authorize('delete', $calendar);
        try {
            $this->calendarService->deleteSchedule($calendar->id);
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Calendar schedule deleted successfully.']);
            }
            return back()->with('success', 'Schedule deleted successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
