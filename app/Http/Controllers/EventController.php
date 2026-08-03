<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use App\Models\Event;
use App\Models\EventLocation;
use App\Models\EventCategory;
use App\Models\EventSchedule;
use App\Models\Ticket;
use App\Models\Notification;
use App\Enums\EventStatus;
use App\Enums\ScheduleStatus;
use App\Enums\TicketStatus;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\Contracts\EventRepositoryInterface;
use Exception;

class EventController extends Controller
{
    use AuthorizesRequests;

    protected EventService $eventService;
    protected EventRepositoryInterface $eventRepository;

    public function __construct(EventService $eventService, EventRepositoryInterface $eventRepository)
    {
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
    }

    /**
     * Display a listing of public events.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'category_id', 'location', 'location_id', 'date', 'status', 'sort']);
        $events = $this->eventRepository->filter($filters, 10);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'event list endpoint.',
                'data' => $events
            ]);
        }

        $user = Auth::user();
        $locations = EventLocation::orderBy('name')->get();
        $categories = EventCategory::orderBy('name')->get();

        $roleName = $user ? (is_object($user->role) ? strtolower($user->role->name ?? '') : strtolower((string)$user->role)) : '';

        if ($roleName === 'organizer') {
            $organizerId = $user->organizerProfile->id ?? 0;
            $events = $this->eventRepository->filter(['organizer_profile_id' => $organizerId], 10);
            return view('organizer.events.index', compact('events'));
        } elseif ($roleName === 'admin') {
            return view('admin.events.index', compact('events'));
        } elseif ($roleName === 'user') {
            return view('user.events.index', compact('events', 'locations', 'categories'));
        }

        return view('user.events.index', compact('events', 'locations', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('organizer.events.create');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $this->authorize('view', $event);

        $event->load(['category', 'location', 'organizerProfile.user', 'schedules', 'tickets']);

        $otherEvents = Event::with(['category', 'location', 'schedules'])
            ->where('id', '!=', $event->id)
            ->whereIn('status', ['published', 'approved'])
            ->latest()
            ->take(3)
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'data' => $event,
                'other_events' => $otherEvents
            ]);
        }

        return view('user.events.show', compact('event', 'otherEvents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        $this->authorize('update', $event);
        return view('organizer.events.edit', compact('event'));
    }

    /**
     * Store a newly created event draft in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $organizerProfile = Auth::user()->organizerProfile;

        if (!$organizerProfile) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Organizer profile required.'], 403);
            }
            return back()->withErrors(['error' => 'Organizer profile required.']);
        }

        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('events/banners', 'public');
                $data['banner'] = $path;
            } elseif ($request->hasFile('banner')) {
                $path = $request->file('banner')->store('events/banners', 'public');
                $data['banner'] = $path;
            } else {
                $data['banner'] = 'events/banners/default.jpg';
            }

            $event = $this->eventService->createDraft($organizerProfile->id, $data);

            // Auto submit to admin verification
            $event->update([
                'status' => EventStatus::Submitted->value ?? 'submitted',
            ]);

            // Create default schedule if start_date provided
            if ($request->filled('start_date')) {
                EventSchedule::create([
                    'event_id' => $event->id,
                    'start_datetime' => $request->start_date,
                    'end_datetime' => $request->end_date ?? $request->start_date,
                    'timezone' => 'Asia/Jakarta',
                    'status' => ScheduleStatus::Published->value ?? 'published',
                ]);
            }

            // Create default ticket if capacity provided
            if ($request->filled('capacity')) {
                $isPaid = $request->input('is_paid', '1') == '1';
                $ticketPrice = $isPaid ? (float) $request->input('price', 0) : 0;
                $ticketName = $isPaid ? 'Tiket Masuk (Berbayar)' : 'Tiket Masuk (Gratis)';

                Ticket::create([
                    'event_id' => $event->id,
                    'name' => $ticketName,
                    'description' => $isPaid ? 'Tiket berbayar ' . $event->title : 'Tiket masuk gratis ' . $event->title,
                    'price' => $ticketPrice,
                    'quota' => (int) $request->capacity,
                    'sold' => 0,
                    'status' => TicketStatus::Active->value ?? 'active',
                ]);
            }

            // Create in-app Notification for organizer
            Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Pengajuan event Berhasil!',
                'message' => 'event "' . $event->title . '" telah berhasil diunggah dan diajukan ke Admin untuk proses verifikasi publikasi.',
                'is_read' => false,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'event submitted for review successfully.',
                    'data' => $event
                ], 201);
            }
            return redirect()->route('organizer.events.index')
                ->with('success', 'event "' . $event->title . '" berhasil diajukan ke Admin untuk verifikasi & publikasi!');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Update the specified event in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        try {
            $this->eventService->updateEvent($event->id, $request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'event updated successfully.'
                ]);
            }
            return redirect()->route('organizer.events.index')->with('success', 'event updated successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event, Request $request)
    {
        $this->authorize('delete', $event);

        try {
            $event->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'event deleted successfully.'
                ]);
            }
            return redirect()->route('organizer.events.index')->with('info', 'event "' . $event->title . '" telah berhasil dihapus.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ==========================================
    // State Transitions
    // ==========================================

    /**
     * Submit an event for review.
     */
    public function submit(Event $event, Request $request)
    {
        $this->authorize('submit', $event);

        try {
            $this->eventService->submit($event->id);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'event submitted for review successfully.'
                ]);
            }
            return back()->with('success', 'event submitted for review successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Publish an event.
     */
    public function publish(Event $event, Request $request)
    {
        $this->authorize('publish', $event);

        try {
            $this->eventService->publish($event->id, Auth::id());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'event published successfully.'
                ]);
            }
            return back()->with('success', 'event published successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Admin: Approve an event (moves Submitted -> UnderReview -> Approved).
     */
    public function approve(Event $event, Request $request)
    {
        $this->authorize('adminApprove', $event);

        try {
            // Move to UnderReview if still Submitted
            if ($event->status === EventStatus::Submitted) {
                $this->eventService->review($event->id);
                $event->refresh();
            }
            $this->eventService->approve($event->id, Auth::id());

            if ($request->wantsJson()) {
                return response()->json(['message' => 'event approved successfully.']);
            }
            return redirect()->route('admin.events.index')->with('success', 'event approved.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Admin: Reject/request revision on an event.
     */
    public function reject(Event $event, Request $request)
    {
        $this->authorize('adminApprove', $event);

        try {
            // Move to UnderReview if still Submitted
            if ($event->status === EventStatus::Submitted) {
                $this->eventService->review($event->id);
                $event->refresh();
            }
            $this->eventService->requestRevision($event->id);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'event rejected and revision requested.']);
            }
            return redirect()->route('admin.events.index')->with('success', 'event sent back for revision.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
