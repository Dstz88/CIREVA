<?php

namespace App\Repositories;

use App\Repositories\Contracts\RoleRepositoryInterface;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository implements RoleRepositoryInterface
{
    /**
     * Get all roles.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Role::all();
    }

    /**
     * Find a role by its ID.
     *
     * @param int $id
     * @return Role|null
     */
    public function findById(int $id): ?Role
    {
        return Role::find($id);
    }

    /**
     * Find a model by ID or throw an exception.
     *
     * @param int $id
     * @return Role
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id): Role
    {
        return Role::findOrFail($id);
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role
    {
        return Role::create($data);
    }

    /**
     * Update an existing role.
     *
     * @param Role $role
     * @param array $data
     * @return bool
     */
    public function update(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    /**
     * Delete a role.
     *
     * @param Role $role
     * @return bool|null
     */
    public function delete(Role $role): ?bool
    {
        return $role->delete();
    }
}

