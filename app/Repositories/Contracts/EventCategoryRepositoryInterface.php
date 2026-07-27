<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\eventCategory;

interface eventCategoryRepositoryInterface
{
    /**
     * Get all event categories.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get paginated event categories.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a category by ID.
     *
     * @param int $id
     * @return eventCategory|null
     */
    public function findById(int $id): ?eventCategory;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return eventCategory
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): eventCategory;

    /**
     * Find a category by slug.
     *
     * @param string $slug
     * @return eventCategory|null
     */
    public function findBySlug(string $slug): ?eventCategory;

    /**
     * Create a new category.
     *
     * @param array $data
     * @return eventCategory
     */
    public function create(array $data): eventCategory;

    /**
     * Update an existing category.
     *
     * @param eventCategory $category
     * @param array $data
     * @return bool
     */
    public function update(eventCategory $category, array $data): bool;

    /**
     * Delete a category.
     *
     * @param eventCategory $category
     * @return bool|null
     */
    public function delete(eventCategory $category): ?bool;
}
