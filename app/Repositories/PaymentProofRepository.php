<?php

namespace App\Repositories;

use App\Repositories\Contracts\PaymentProofRepositoryInterface;

use App\Models\PaymentProof;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentProofRepository implements PaymentProofRepositoryInterface
{
    /**
     * Get all payment proofs.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return PaymentProof::all();
    }

    /**
     * Get paginated payment proofs.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return PaymentProof::paginate($perPage);
    }

    /**
     * Find a payment proof by ID.
     *
     * @param int $id
     * @return PaymentProof|null
     */
    public function findById(int $id): ?PaymentProof
    {
        return PaymentProof::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return PaymentProof
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): PaymentProof
    {
        return PaymentProof::findOrFail($id);
    }

    /**
     * Create a new payment proof.
     *
     * @param array $data
     * @return PaymentProof
     */
    public function create(array $data): PaymentProof
    {
        return PaymentProof::create($data);
    }

    /**
     * Update an existing payment proof.
     *
     * @param PaymentProof $paymentProof
     * @param array $data
     * @return bool
     */
    public function update(PaymentProof $paymentProof, array $data): bool
    {
        return $paymentProof->update($data);
    }

    /**
     * Delete a payment proof.
     *
     * @param PaymentProof $paymentProof
     * @return bool|null
     */
    public function delete(PaymentProof $paymentProof): ?bool
    {
        return $paymentProof->delete();
    }
}

