<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Exception;

class BookingController extends Controller
{
    use AuthorizesRequests;

    protected BookingService $bookingService;
    protected BookingRepositoryInterface $bookingRepository;

    public function __construct(BookingService $bookingService, BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = is_object($user?->role) ? strtolower($user->role->name ?? '') : strtolower((string)($user?->role ?? ''));
        if ($user && $roleName === 'organizer') {
            $organizerProfileId = $user->organizerProfile->id ?? 0;
            $bookings = $this->bookingRepository->findByOrganizerProfile($organizerProfileId, 15);

            if ($request->wantsJson()) {
                return response()->json(['data' => $bookings]);
            }
            return view('organizer.bookings.index', compact('bookings'));
        }

        $userId = $user->id ?? 0;
        $statusFilter = $request->get('status');

        if ($statusFilter === 'pending') {
            $bookings = $this->bookingRepository->findByUserAndStatus($userId, ['pending', 'waiting_payment', 'pending_verification'], 15);
        } elseif ($statusFilter === 'paid') {
            $bookings = $this->bookingRepository->findByUserAndStatus($userId, ['paid', 'payment_completed', 'confirmed'], 15);
        } elseif ($statusFilter === 'cancelled') {
            $bookings = $this->bookingRepository->findByUserAndStatus($userId, ['cancelled', 'expired'], 15);
        } else {
            $bookings = $this->bookingRepository->findByUser($userId, 15);
        }

        if ($request->wantsJson()) {
            return response()->json(['data' => $bookings]);
        }
        return view('user.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking, Request $request)
    {
        $this->authorize('view', $booking);

        $booking->load(['items.ticket.event', 'transaction.paymentProof']);

        $statusVal = is_object($booking->status) ? $booking->status->value : $booking->status;
        if (!$booking->transaction && in_array($statusVal, ['pending', 'waiting_payment'])) {
            try {
                app(\App\Services\TransactionService::class)->createTransaction(
                    $booking->id,
                    'QRIS / Bank Transfer',
                    $booking->total_amount > 0 ? $booking->total_amount : 1
                );
                $booking->load('transaction');
            } catch (Exception $e) {
                // handle gracefully
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['data' => $booking]);
        }

        $user = Auth::user();
        $roleName = is_object($user?->role) ? strtolower($user->role->name ?? '') : strtolower((string)($user?->role ?? ''));
        if ($user && $roleName === 'organizer') {
            return view('organizer.bookings.show', compact('booking'));
        }

        return view('user.bookings.show', compact('booking'));
    }

    public function create(Request $request)
    {
        $eventId = $request->query('event_id');
        if (!$eventId) {
            return redirect()->route('events.index')->with('warning', 'Pilih event terlebih dahulu.');
        }

        $event = \App\Models\event::with(['category', 'location', 'tickets', 'schedules'])->findOrFail($eventId);

        $selectedTicketId = $request->query('ticket_id');
        $selectedTicket = $event->tickets->where('id', $selectedTicketId)->first() ?? $event->tickets->first();

        $schedules = $event->schedules;
        $datesList = [];
        if ($schedules && $schedules->count() > 0) {
            foreach ($schedules as $sched) {
                if ($sched->start_datetime) {
                    $cDate = \Carbon\Carbon::parse($sched->start_datetime);
                    $datesList[] = [
                        'date' => $cDate->format('d M Y'),
                        'day' => $cDate->translatedFormat('l'),
                        'label' => $cDate->format('d M Y') . ' (' . $cDate->translatedFormat('l') . ')',
                    ];
                }
            }
        }

        if (empty($datesList)) {
            $baseDate = now()->addDays(1);
            for ($i = 0; $i < 4; $i++) {
                $d = $baseDate->copy()->addDays($i);
                $datesList[] = [
                    'date' => $d->format('d M Y'),
                    'day' => $d->translatedFormat('l'),
                    'label' => $d->format('d M Y') . ' (' . $d->translatedFormat('l') . ')',
                ];
            }
        }

        $selectedDateFormatted = $datesList[0]['label'] ?? now()->format('d M Y');

        return view('user.bookings.create', compact('event', 'selectedTicket', 'datesList', 'selectedDateFormatted'));
    }

    public function store(StoreBookingRequest $request)
    {
        try {
            // Note: The previous logic expected $ticketQuantities[$item['ticket_id']] = $item['quantity']
            // But the BookingService createBooking function expects array of ['ticket_id' => x, 'quantity' => y]
            // Let's pass the tickets array directly.
            $items = $request->validated('tickets');

            $booking = $this->bookingService->createBooking(Auth::id(), $items);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Booking created successfully.',
                    'data' => $booking
                ], 201);
            }
            return redirect()->route('user.bookings.show', $booking)->with('success', 'Booking created successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function cancel(Booking $booking, Request $request)
    {
        $this->authorize('delete', $booking);

        try {
            $this->bookingService->cancelBooking($booking->id);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Booking cancelled successfully.']);
            }
            return redirect()->route('user.bookings.index')->with('success', 'Pemesanan tiket berhasil dibatalkan. Kuota tiket telah dikembalikan.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
