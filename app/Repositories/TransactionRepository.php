<?php

namespace App\Repositories;

use App\Repositories\Contracts\TransactionRepositoryInterface;

use App\Models\Transaction;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * Get all transactions.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Transaction::all();
    }

    /**
     * Get paginated transactions.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Transaction::paginate($perPage);
    }

    /**
     * Find a transaction by ID.
     *
     * @param int $id
     * @return Transaction|null
     */
    public function findById(int $id): ?Transaction
    {
        return Transaction::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Transaction
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Transaction
    {
        return Transaction::findOrFail($id);
    }

    /**
     * Create a new transaction.
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    /**
     * Update an existing transaction.
     *
     * @param Transaction $transaction
     * @param array $data
     * @return bool
     */
    public function update(Transaction $transaction, array $data): bool
    {
        return $transaction->update($data);
    }

    /**
     * Delete a transaction.
     *
     * @param Transaction $transaction
     * @return bool|null
     */
    public function delete(Transaction $transaction): ?bool
    {
        return $transaction->delete();
    }

    /**
     * Find a transaction by booking ID.
     *
     * @param int $bookingId
     * @return Transaction|null
     */
    public function findByBooking(int $bookingId): ?Transaction
    {
        return Transaction::where('booking_id', $bookingId)->first();
    }

    /**
     * Find paginated transactions by status.
     *
     * @param TransactionStatus $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByStatus(TransactionStatus $status, int $perPage = 15): LengthAwarePaginator
    {
        return Transaction::where('status', $status)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get total sum of successful transactions.
     *
     * @return float
     */
    public function getTotalSuccessfulRevenue(): float
    {
        return (float) Transaction::whereIn('status', [TransactionStatus::Success, 'success', 'paid'])->sum('amount');
    }
}

