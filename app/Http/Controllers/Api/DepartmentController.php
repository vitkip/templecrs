<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Services\DepartmentService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartmentController extends Controller
{
    public function __construct(
        protected DepartmentService $service
    ) {}

    /**
     * GET /api/departments
     */
    public function index(): AnonymousResourceCollection
    {
        return DepartmentResource::collection($this->service->list());
    }

    /**
     * GET /api/departments/{id}
     */
    public function show(int $id): DepartmentResource
    {
        return new DepartmentResource($this->service->find($id));
    }
}
