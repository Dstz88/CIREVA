<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Enums\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionVerificationController extends Controller
{
    /**
     * Display listing of all transactions for Admin monitoring & verification.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with(['booking.user', 'booking.items.ticket.event', 'paymentProof']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10);

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Verify payment transaction as successful and issue tickets.
     */
    public function verify(Transaction $transaction): RedirectResponse
    {
        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => TransactionStatus::Success->value ?? 'success',
            ]);

            if ($transaction->booking) {
                $transaction->booking->update([
                    'status' => BookingStatus::PaymentCompleted->value ?? 'payment_completed',
                ]);
            }
        });

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi #' . $transaction->transaction_number . ' berhasil diverifikasi & e-tiket diterbitkan!');
    }

    /**
     * Reject payment transaction with notes.
     */
    public function reject(Request $request, Transaction $transaction): RedirectResponse
    {
        $request->validate([
            'notes' => ['required', 'string', 'max:500'],
        ], [
            'notes.required' => 'Catatan penolakan transaksi wajib diisi.',
        ]);

        DB::transaction(function () use ($request, $transaction) {
            $transaction->update([
                'status' => TransactionStatus::Failed->value ?? 'failed',
            ]);

            if ($transaction->booking) {
                $transaction->booking->update([
                    'status' => BookingStatus::Cancelled->value ?? 'cancelled',
                ]);
            }
        });

        return redirect()->route('admin.transactions.index')
            ->with('warning', 'Transaksi #' . $transaction->transaction_number . ' telah ditolak.');
    }
}
