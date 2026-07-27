<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Transaction;
use App\Enums\TransactionStatus;

interface TransactionRepositoryInterface
{
    /**
     * Get all transactions.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated transactions.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a transaction by ID.
     *
     * @param int $id
     * @return Transaction|null
     */
    public function findById(int $id): ?Transaction;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Transaction
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Transaction;

    /**
     * Create a new transaction.
     *
     * @param array $data
     * @return Transaction
     */
    public function create(array $data): Transaction;

    /**
     * Update an existing transaction.
     *
     * @param Transaction $transaction
     * @param array $data
     * @return bool
     */
    public function update(Transaction $transaction, array $data): bool;

    /**
     * Delete a transaction.
     *
     * @param Transaction $transaction
     * @return bool|null
     */
    public function delete(Transaction $transaction): ?bool;

    /**
     * Find a transaction by booking ID.
     *
     * @param int $bookingId
     * @return Transaction|null
     */
    public function findByBooking(int $bookingId): ?Transaction;

    /**
     * Find paginated transactions by status.
     *
     * @param TransactionStatus $status
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findByStatus(TransactionStatus $status, int $perPage = 15): LengthAwarePaginator;

    /**
     * Get total sum of successful transactions.
     *
     * @return float
     */
    public function getTotalSuccessfulRevenue(): float;
}

