<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\OrganizerDocument;

interface OrganizerDocumentRepositoryInterface
{
    /**
     * Get all organizer documents.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated organizer documents.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a document by ID.
     *
     * @param int $id
     * @return OrganizerDocument|null
     */
    public function findById(int $id): ?OrganizerDocument;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return OrganizerDocument
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): OrganizerDocument;

    /**
     * Create a new document.
     *
     * @param array $data
     * @return OrganizerDocument
     */
    public function create(array $data): OrganizerDocument;

    /**
     * Update an existing document.
     *
     * @param OrganizerDocument $document
     * @param array $data
     * @return bool
     */
    public function update(OrganizerDocument $document, array $data): bool;

    /**
     * Delete a document.
     *
     * @param OrganizerDocument $document
     * @return bool|null
     */
    public function delete(OrganizerDocument $document): ?bool;

    /**
     * Get all documents belonging to a specific organizer profile.
     *
     * @param int $organizerProfileId
     * @return Collection
     */
    public function getByOrganizerProfile(int $organizerProfileId): Collection;

    /**
     * Get pending documents paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPending(int $perPage = 15): LengthAwarePaginator;
}

