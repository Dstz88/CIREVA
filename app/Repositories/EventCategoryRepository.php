<?php

namespace App\Repositories;

use App\Repositories\Contracts\eventCategoryRepositoryInterface;

use App\Models\eventCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class eventCategoryRepository implements eventCategoryRepositoryInterface
{
    /**
     * Get all event categories.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return eventCategory::all();
    }

    /**
     * Get paginated event categories.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return eventCategory::paginate($perPage);
    }

    /**
     * Find a category by ID.
     *
     * @param int $id
     * @return eventCategory|null
     */
    public function findById(int $id): ?eventCategory
    {
        return eventCategory::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return eventCategory
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): eventCategory
    {
        return eventCategory::findOrFail($id);
    }

    /**
     * Find a category by slug.
     *
     * @param string $slug
     * @return eventCategory|null
     */
    public function findBySlug(string $slug): ?eventCategory
    {
        return eventCategory::where('slug', $slug)->first();
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return eventCategory
     */
    public function create(array $data): eventCategory
    {
        return eventCategory::create($data);
    }

    /**
     * Update an existing category.
     *
     * @param eventCategory $category
     * @param array $data
     * @return bool
     */
    public function update(eventCategory $category, array $data): bool
    {
        return $category->update($data);
    }

    /**
     * Delete a category.
     *
     * @param eventCategory $category
     * @return bool|null
     */
    public function delete(eventCategory $category): ?bool
    {
        return $category->delete();
    }
}
