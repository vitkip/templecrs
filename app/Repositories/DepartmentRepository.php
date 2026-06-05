<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository
{
    public function __construct(
        protected Department $model
    ) {}

    /**
     * Get all active departments ordered.
     */
    public function getAll(): Collection
    {
        return $this->model
            ->with('head')
            ->withCount(['personnel' => fn ($q) => $q->where('is_active', true)])
            ->active()
            ->ordered()
            ->get();
    }

    /**
     * Get all departments for dropdown selects.
     */
    public function getForSelect(): Collection
    {
        return $this->model->active()->ordered()->get(['id', 'name_lo', 'name_en']);
    }

    /**
     * Get a single department by ID.
     */
    public function getById(int $id): Department
    {
        return $this->model
            ->with(['head', 'personnel' => fn ($q) => $q->active()->ordered()])
            ->findOrFail($id);
    }

    /**
     * Create a new department.
     */
    public function create(array $data): Department
    {
        return $this->model->create($data);
    }

    /**
     * Update an existing department.
     */
    public function update(int $id, array $data): Department
    {
        $department = $this->model->findOrFail($id);
        $department->update($data);
        return $department->fresh();
    }

    /**
     * Soft delete a department.
     */
    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
