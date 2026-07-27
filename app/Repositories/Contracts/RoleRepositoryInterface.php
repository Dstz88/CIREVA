<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Role;

interface RoleRepositoryInterface
{
    /**
     * Get all roles.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Find a role by its ID.
     *
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role;

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Role
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Role;

    /**
     * Create a new role.
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role;

    /**
     * Update an existing role.
     *
     * @param Role $role
     * @param array $data
     * @return bool
     */
    public function update(Role $role, array $data): bool;

    /**
     * Delete a role.
     *
     * @param Role $role
     * @return bool|null
     */
    public function delete(Role $role): ?bool;
}

