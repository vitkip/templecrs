<?php

namespace App\Repositories;

use App\Models\Personnel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PersonnelRepository
{
    public function __construct(
        protected Personnel $model
    ) {}

    /**
     * Get paginated personnel with optional filters.
     */
    public function getAll(
        ?string $search = null,
        ?string $gender = null,
        ?int $departmentId = null,
        ?bool $isActive = null,
        int $perPage = 15,
        string $sortBy = 'sort_order',
        string $sortDir = 'asc'
    ): LengthAwarePaginator {
        return $this->model
            ->with('department')
            ->search($search)
            ->when($gender, fn (Builder $q) => $q->where('gender', $gender))
            ->when($departmentId, fn (Builder $q) => $q->where('department_id', $departmentId))
            ->when($isActive !== null, fn (Builder $q) => $q->where('is_active', $isActive))
            ->orderBy($sortBy, $sortDir)
            ->paginate($perPage);
    }

    /**
     * Get a single personnel record by ID.
     */
    public function getById(int $id): ?Personnel
    {
        return $this->model->with('department')->findOrFail($id);
    }

    /**
     * Create a new personnel record.
     */
    public function create(array $data): Personnel
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing personnel record.
     */
    public function update(int $id, array $data): Personnel
    {
        $personnel = $this->model->findOrFail($id);
        $personnel->update($data);
        return $personnel->fresh();
    }

    /**
     * Soft delete a personnel record.
     */
    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    /**
     * Get personnel by department.
     */
    public function getByDepartment(int $departmentId): Collection
    {
        return $this->model
            ->where('department_id', $departmentId)
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get only monks.
     */
    public function getMonks(): Collection
    {
        return $this->model->monks()->active()->ordered()->get();
    }

    /**
     * Get only laypersons.
     */
    public function getLaypersons(): Collection
    {
        return $this->model->laypersons()->active()->ordered()->get();
    }

    /**
     * Get statistics for dashboard.
     */
    public function getStatistics(): array
    {
        $total = $this->model->count();
        $active = $this->model->active()->count();
        $monks = $this->model->monks()->count();
        $laypersons = $this->model->laypersons()->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active,
            'monks' => $monks,
            'laypersons' => $laypersons,
            'active_ratio' => $total > 0 ? round(($active / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(int $id): Personnel
    {
        $personnel = $this->model->findOrFail($id);
        $personnel->update(['is_active' => !$personnel->is_active]);
        return $personnel->fresh();
    }
}
