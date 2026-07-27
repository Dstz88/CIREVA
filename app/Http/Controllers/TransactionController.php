<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Models\Transaction;
use App\Http\Requests\UploadPaymentProofRequest;
use App\Http\Requests\VerifyPaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = is_object($user?->role) ? strtolower($user->role->name ?? '') : strtolower((string)($user?->role ?? ''));
        if ($user && $roleName === 'admin') {
            $transactions = Transaction::with('booking.user')->paginate(15);
            if ($request->wantsJson()) {
                return response()->json(['data' => $transactions]);
            }
            return view('admin.transactions.index', compact('transactions'));
        }

        $transactions = Transaction::with('booking.user')
            ->whereHas('booking', function($q) use ($user) {
                $q->where('user_id', $user->id ?? 0);
            })->paginate(15);
            
        if ($request->wantsJson()) {
            return response()->json(['data' => $transactions]);
        }
        return view('user.payments.index', compact('transactions'));
    }

    public function show(Transaction $transaction, Request $request)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['booking.items.ticket', 'paymentProof']);

        if ($request->wantsJson()) {
            return response()->json(['data' => $transaction]);
        }
        
        // No specific view for show yet, but we will return it if needed
        return response()->json(['data' => $transaction]);
    }

    public function uploadProof(UploadPaymentProofRequest $request, Transaction $transaction)
    {
        try {
            $proofPath = $request->file('proof_file')
                ? $request->file('proof_file')->store('payment-proofs', 'public')
                : ($request->input('proof_url') ?? 'payment-proofs/default.jpg');

            $this->transactionService->uploadPaymentProof(
                $transaction->id,
                $proofPath
            );
            
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Payment proof uploaded successfully.']);
            }
            return back()->with('success', 'Payment proof uploaded successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function verify(VerifyPaymentRequest $request, Transaction $transaction)
    {
        try {
            $isSuccess = (bool) $request->validated('is_success');

            if ($isSuccess) {
                $this->transactionService->verifySuccess($transaction->id);
            } else {
                $this->transactionService->markAsFailed($transaction->id);
            }
            
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Transaction verification processed successfully.']);
            }
            return back()->with('success', 'Transaction verification processed successfully.');
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
