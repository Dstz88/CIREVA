<?php

namespace App\Services;

use App\Repositories\Contracts\TransactionRepositoryInterface;
use App\Repositories\Contracts\PaymentProofRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Models\Transaction;
use App\Models\PaymentProof;
use App\Enums\TransactionStatus;
use App\Enums\BookingStatus;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class TransactionService
{
    protected TransactionRepositoryInterface $transactionRepository;
    protected PaymentProofRepositoryInterface $paymentProofRepository;
    protected BookingService $bookingService;
    protected BookingRepositoryInterface $bookingRepository;

    public function __construct(
        TransactionRepositoryInterface $transactionRepository,
        PaymentProofRepositoryInterface $paymentProofRepository,
        BookingService $bookingService,
        BookingRepositoryInterface $bookingRepository
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->paymentProofRepository = $paymentProofRepository;
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Create a new transaction for a booking.
     * State transition: Null -> Pending
     *
     * @param int $bookingId
     * @param string $paymentMethod
     * @param float $amount
     * @return Transaction
     * @throws Exception
     */
    public function createTransaction(int $bookingId, string $paymentMethod, float $amount): Transaction
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);

        if ($booking->status !== BookingStatus::Pending) {
            throw new Exception("Transactions can only be created for pending bookings.");
        }

        if ($amount <= 0) {
            throw new InvalidArgumentException("Transaction amount must be greater than zero.");
        }

        $data = [
            'booking_id' => $bookingId,
            'transaction_number' => 'TRX-' . strtoupper(Str::random(10)),
            'payment_method' => $paymentMethod,
            'amount' => $amount,
            'status' => TransactionStatus::Pending,
        ];

        return $this->transactionRepository->create($data);
    }

    /**
     * Upload payment proof for a transaction.
     *
     * @param int $transactionId
     * @param string $filePath
     * @return PaymentProof
     * @throws Exception
     */
    public function uploadPaymentProof(int $transactionId, string $filePath): PaymentProof
    {
        $transaction = $this->transactionRepository->findOrFail($transactionId);

        if ($transaction->status !== TransactionStatus::Pending) {
            throw new Exception("Payment proof can only be uploaded for pending transactions.");
        }

        return $this->paymentProofRepository->create([
            'transaction_id' => $transactionId,
            'file_path' => $filePath,
            'uploaded_at' => now(),
        ]);
    }

    /**
     * Verify and mark a transaction as successful.
     * Also updates the associated booking to Paid.
     * State transition: Pending -> Success
     *
     * @param int $transactionId
     * @param string|null $externalTransactionId
     * @return bool
     * @throws Exception
     */
    public function verifySuccess(int $transactionId, ?string $externalTransactionId = null): bool
    {
        $transaction = $this->transactionRepository->findOrFail($transactionId);

        if ($transaction->status !== TransactionStatus::Pending) {
            throw new Exception("Only pending transactions can be verified as successful.");
        }

        return DB::transaction(function () use ($transaction, $externalTransactionId) {
            $data = ['status' => TransactionStatus::Success];
            
            if ($externalTransactionId) {
                $data['transaction_id'] = $externalTransactionId;
            }

            $updated = $this->transactionRepository->update($transaction, $data);

            if ($updated) {
                // Business Rule: Booking menjadi Paid hanya jika transaksi Success.
                $this->bookingService->markAsPaid($transaction->booking_id);
            }

            return $updated;
        });
    }

    /**
     * Mark a transaction as failed.
     * State transition: Pending -> Failed
     *
     * @param int $transactionId
     * @return bool
     * @throws Exception
     */
    public function markAsFailed(int $transactionId): bool
    {
        $transaction = $this->transactionRepository->findOrFail($transactionId);

        if ($transaction->status !== TransactionStatus::Pending) {
            throw new Exception("Only pending transactions can be marked as failed.");
        }

        return $this->transactionRepository->update($transaction, ['status' => TransactionStatus::Failed]);
    }
}
