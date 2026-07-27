<?php

namespace App\Repositories;

use App\Repositories\Contracts\OrganizerDocumentRepositoryInterface;

use App\Models\OrganizerDocument;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizerDocumentRepository implements OrganizerDocumentRepositoryInterface
{
    /**
     * Get all organizer documents.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return OrganizerDocument::all();
    }

    /**
     * Get paginated organizer documents.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return OrganizerDocument::paginate($perPage);
    }

    /**
     * Find a document by ID.
     *
     * @param int $id
     * @return OrganizerDocument|null
     */
    public function findById(int $id): ?OrganizerDocument
    {
        return OrganizerDocument::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return OrganizerDocument
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): OrganizerDocument
    {
        return OrganizerDocument::findOrFail($id);
    }

    /**
     * Create a new document.
     *
     * @param array $data
     * @return OrganizerDocument
     */
    public function create(array $data): OrganizerDocument
    {
        return OrganizerDocument::create($data);
    }

    /**
     * Update an existing document.
     *
     * @param OrganizerDocument $document
     * @param array $data
     * @return bool
     */
    public function update(OrganizerDocument $document, array $data): bool
    {
        return $document->update($data);
    }

    /**
     * Delete a document.
     *
     * @param OrganizerDocument $document
     * @return bool|null
     */
    public function delete(OrganizerDocument $document): ?bool
    {
        return $document->delete();
    }

    /**
     * Get all documents belonging to a specific organizer profile.
     *
     * @param int $organizerProfileId
     * @return Collection
     */
    public function getByOrganizerProfile(int $organizerProfileId): Collection
    {
        return OrganizerDocument::where('organizer_profile_id', $organizerProfileId)->get();
    }

    /**
     * Get pending documents paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPending(int $perPage = 15): LengthAwarePaginator
    {
        return OrganizerDocument::pending()->paginate($perPage);
    }
}

