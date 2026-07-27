<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use App\Models\Ticket;
use App\Models\event;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;

class TicketController extends Controller
{
    use AuthorizesRequests;

    protected TicketService $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        $tickets = Ticket::with('event')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Ticket list endpoint.',
                'data' => $tickets
            ]);
        }

        $user = Auth::user();
        $roleName = is_object($user?->role) ? strtolower($user->role->name ?? '') : strtolower((string)($user?->role ?? ''));

        if ($user && $roleName === 'organizer') {
            return view('organizer.tickets.index', compact('tickets'));
        } elseif ($user && $roleName === 'admin') {
            return view('admin.tickets.index', compact('tickets'));
        }

        $userBookings = \App\Models\Booking::with(['items.ticket.event', 'transaction'])
            ->where('user_id', $user?->id)
            ->whereIn('status', ['paid', 'payment_completed', 'confirmed'])
            ->latest()
            ->get();

        return view('user.tickets.index', compact('tickets', 'userBookings'));
    }

    public function create(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Ticket create endpoint.']);
        }
        return view('organizer.tickets.create');
    }

    public function store(StoreTicketRequest $request)
    {
        $this->authorize('create', Ticket::class);
        $event = event::findOrFail($request->validated('event_id'));
        $this->authorize('update', $event);

        try {
            $ticket = $this->ticketService->createTicket($event->id, $request->validated());
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Ticket created successfully.',
                    'data' => $ticket
                ], 201);
            }
            return redirect()->route('organizer.tickets.index')->with('success', 'Ticket created successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit(Ticket $ticket, Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Ticket edit endpoint.']);
        }
        return view('organizer.tickets.edit', compact('ticket'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        try {
            $this->ticketService->updateTicket($ticket->id, $request->validated());
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Ticket updated successfully.']);
            }
            return redirect()->route('organizer.tickets.index')->with('success', 'Ticket updated successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Ticket $ticket, Request $request)
    {
        $this->authorize('delete', $ticket);

        try {
            $this->ticketService->deleteTicket($ticket->id);
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Ticket deleted successfully.']);
            }
            return back()->with('success', 'Ticket deleted successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // ==========================================
    // Status Management
    // ==========================================

    public function activate(Ticket $ticket, Request $request)
    {
        $this->authorize('manageStatus', $ticket);

        try {
            $this->ticketService->activateTicket($ticket->id);
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Ticket activated successfully.']);
            }
            return back()->with('success', 'Ticket activated successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function deactivate(Ticket $ticket, Request $request)
    {
        $this->authorize('manageStatus', $ticket);

        try {
            $this->ticketService->deactivateTicket($ticket->id);
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Ticket deactivated successfully.']);
            }
            return back()->with('success', 'Ticket deactivated successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
