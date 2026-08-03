<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Ticket;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Organizer\SpkController as OrganizerSpkController;
use App\Http\Controllers\Organizer\ReportController as OrganizerReportController;
use App\Http\Controllers\Organizer\MasterDataController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrganizerVerificationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SpkController as AdminSpkController;
use App\Http\Controllers\Admin\EventVerificationController;
use App\Http\Controllers\Admin\TransactionVerificationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $publishedEvents = Event::with(['category', 'location', 'tickets', 'schedules'])
        ->where('status', 'published')
        ->where('created_at', '>=', now()->subDays(7))
        ->latest()
        ->take(6)
        ->get();

    $featuredEvent = $publishedEvents->first() ?? Event::with(['category', 'location', 'tickets', 'schedules'])
        ->where('created_at', '>=', now()->subDays(7))
        ->first();

    $publishedevents = $publishedEvents;
    $featuredevent = $featuredEvent;

    return view('welcome', compact('publishedEvents', 'featuredEvent', 'publishedevents', 'featuredevent'));
})->name('Beranda');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/about', fn () => view('about'))->name('about');

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user) {
        if ($user->hasRole('admin')) return redirect('/admin/dashboard');
        if ($user->hasRole('organizer')) return redirect('/organizer/dashboard');
    }
    return redirect('/user/dashboard');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Notifications
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        $popularEvents = Event::with(['category', 'location', 'tickets'])
            ->where('status', 'published')
            ->latest()
            ->take(4)
            ->get();

        $upcomingEvents = Event::with(['category', 'location'])
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        $bookingCount = Booking::where('user_id', Auth::id())->count();
        $ticketCount = Ticket::whereHas('bookingItems.booking', function ($q) {
            $q->where('user_id', Auth::id());
        })->count();

        $popularevents = $popularEvents;
        $upcomingevents = $upcomingEvents;

        return view('user.dashboard', compact('popularEvents', 'upcomingEvents', 'popularevents', 'upcomingevents', 'bookingCount', 'ticketCount'));
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('bookings', BookingController::class)->only(['index', 'create', 'show', 'store']);
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/payments', [TransactionController::class, 'index'])->name('payments.index');
    Route::post('/payments/{transaction}/upload', [TransactionController::class, 'uploadProof'])->name('payments.upload');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/settings', fn () => redirect()->route('user.profile.edit'))->name('settings');
});

/*
|--------------------------------------------------------------------------
| Organizer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:organizer', 'EnsureProfileCompleted'])
    ->prefix('organizer')
    ->name('organizer.')
    ->group(function () {
        Route::get('/dashboard', fn () => view('organizer.dashboard'))->name('dashboard');

        Route::get('/profile', [OrganizerController::class, 'show'])->name('profile.show');
        Route::put('/profile', [OrganizerController::class, 'update'])->name('profile.update');

        Route::get('/documents', [OrganizerController::class, 'documents'])->name('documents.index');
        Route::post('/documents', [OrganizerController::class, 'uploadDocument'])->name('documents.store');

        Route::get('/spk', [OrganizerSpkController::class, 'index'])->name('spk.index');
        Route::get('/spk/export-pdf', [OrganizerSpkController::class, 'exportPdf'])->name('spk.export-pdf');
        Route::post('/spk/sign', [OrganizerSpkController::class, 'sign'])->name('spk.sign');

        // Locked features until Admin Verification & Approved SPK
        Route::middleware(['EnsureSpkApproved', 'EnsureOrganizerVerified'])->group(function () {
            Route::resource('events', EventController::class)->except(['show']);
            Route::post('/locations', [MasterDataController::class, 'storeLocation'])->name('locations.store');

            Route::resource('calendar', CalendarController::class)->except(['show']);
            Route::resource('tickets', TicketController::class)->except(['show']);
            Route::post('/tickets/{ticket}/activate', [TicketController::class, 'activate'])->name('tickets.activate');
            Route::post('/tickets/{ticket}/deactivate', [TicketController::class, 'deactivate'])->name('tickets.deactivate');

            Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
            Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

            Route::get('/reports', [OrganizerReportController::class, 'index'])->name('reports.index');
        });
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/organizer-verifications', [OrganizerVerificationController::class, 'index'])->name('organizer-verifications.index');
    Route::get('/organizer-verifications/{organizerVerification}', [OrganizerVerificationController::class, 'show'])->name('organizer-verifications.show');
    Route::put('/organizer-verifications/{organizerVerification}/approve', [OrganizerVerificationController::class, 'approve'])->name('organizer-verifications.approve');
    Route::put('/organizer-verifications/{organizerVerification}/reject', [OrganizerVerificationController::class, 'reject'])->name('organizer-verifications.reject');
    Route::delete('/organizer-verifications/{organizerVerification}', [OrganizerVerificationController::class, 'destroy'])->name('organizer-verifications.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    Route::get('/spk', [AdminSpkController::class, 'index'])->name('spk.index');
    Route::put('/spk/{agreement}/approve', [AdminSpkController::class, 'approve'])->name('spk.approve');
    Route::put('/spk/{agreement}/reject', [AdminSpkController::class, 'reject'])->name('spk.reject');
    Route::delete('/spk/{agreement}', [AdminSpkController::class, 'destroy'])->name('spk.destroy');

    Route::get('/events', [EventVerificationController::class, 'index'])->name('events.index');
    Route::put('/events/{event}/approve', [EventVerificationController::class, 'approve'])->name('events.approve');
    Route::put('/events/{event}/reject', [EventVerificationController::class, 'reject'])->name('events.reject');
    Route::delete('/events/{event}', [EventVerificationController::class, 'destroy'])->name('events.destroy');

    Route::resource('calendars', CalendarController::class)->except(['show']);
    Route::resource('tickets', TicketController::class)->except(['show']);

    Route::get('/transactions', [TransactionVerificationController::class, 'index'])->name('transactions.index');
    Route::put('/transactions/{transaction}/verify', [TransactionVerificationController::class, 'verify'])->name('transactions.verify');
    Route::put('/transactions/{transaction}/reject', [TransactionVerificationController::class, 'reject'])->name('transactions.reject');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
});

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Debug Login Route
|--------------------------------------------------------------------------
*/
Route::get('/debug-login', function () {
    $user = \App\Models\User::where('email', 'evalitamaria00@gmail.com')->first();
    
    if (!$user) {
        return response()->json([
            'status' => 'not_found',
            'message' => 'User evalitamaria00@gmail.com tidak ditemukan di database'
        ]);
    }
    
    $profile = $user->organizerProfile;
    
    return response()->json([
        'status' => 'found',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'has_profile' => (bool) $profile,
            'profile_status' => $profile?->status?->value ?? null,
        ]
    ]);
});