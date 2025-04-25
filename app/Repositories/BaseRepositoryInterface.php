<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Get all records
     */
    public function all(): Collection;

    /**
     * Get all records paginated
     */
    public function paginated(string $sortProperty = 'id',
                              string $sortDirection = 'asc',
                              int $perPage = 10,
                              ?array $filters = null): LengthAwarePaginator;

    /**
     * Find a record by its ID
     */
    public function findOrFail(int $id): ?Model;

    /**
     * Find a record by its ID and lock
     */
    public function findAndLockForUpdate(int $id): ?Model;

    /**
     * Create a new record
     */
    public function create(array $data): Model;

    /**
     * Update an existing record
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a record by its ID
     */
    public function delete(int $id): bool;
}
