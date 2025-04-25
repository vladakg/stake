<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     */
    public function all(): Collection
    {
        return $this->model::all();
    }


    /**
     * Get all records paginated
     */
    public function paginated(string $sortProperty = 'id',
                              string $sortDirection = 'asc',
                              int $perPage = 10,
                              ?array $filters = null): LengthAwarePaginator
    {
        return new LengthAwarePaginator(collect(), 0, $perPage);
    }

    /**
     * Find a record by its ID
     */
    public function findOrFail(int $id): ?Model
    {
        return $this->model::findOrFail($id);
    }

    /**
     * Find a record by its ID and lock
     */
    public function findAndLockForUpdate(int $id): ?Model
    {
        return DB::transaction(function () use ($id) {
            return $this->model::where('id', $id)->lockForUpdate()->firstOrFail();
        });
    }

    /**
     * Create a new record
     */
    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    /**
     * Update an existing record
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->findOrFail($id);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    /**
     * Delete a record by its ID
     */
    public function delete(int $id): bool
    {
        $record = $this->find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
