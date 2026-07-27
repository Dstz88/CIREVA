<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\PaymentProof;

interface PaymentProofRepositoryInterface
{
    /**
     * Get all payment proofs.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated payment proofs.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a payment proof by ID.
     *
     * @param int $id
     * @return PaymentProof|null
     */
    public function findById(int $id): ?PaymentProof;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return PaymentProof
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): PaymentProof;

    /**
     * Create a new payment proof.
     *
     * @param array $data
     * @return PaymentProof
     */
    public function create(array $data): PaymentProof;

    /**
     * Update an existing payment proof.
     *
     * @param PaymentProof $paymentProof
     * @param array $data
     * @return bool
     */
    public function update(PaymentProof $paymentProof, array $data): bool;

    /**
     * Delete a payment proof.
     *
     * @param PaymentProof $paymentProof
     * @return bool|null
     */
    public function delete(PaymentProof $paymentProof): ?bool;
}

