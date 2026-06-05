<?php

namespace App\Services;

use App\Repositories\DepartmentRepository;
use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentService
{
    public function __construct(
        protected DepartmentRepository $repository
    ) {}

    public function list(): Collection
    {
        return $this->repository->getAll();
    }

    public function getForSelect(): Collection
    {
        return $this->repository->getForSelect();
    }

    public function find(int $id): Department
    {
        return $this->repository->getById($id);
    }

    public function create(array $data): Department
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): Department
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
