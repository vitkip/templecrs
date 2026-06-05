<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use App\Http\Resources\PersonnelResource;
use App\Services\PersonnelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PersonnelController extends Controller
{
    public function __construct(
        protected PersonnelService $service
    ) {}

    /**
     * GET /api/personnel
     * List paginated personnel with optional filters.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $personnel = $this->service->list(
            search: $request->get('search'),
            gender: $request->get('gender'),
            departmentId: $request->get('department_id') ? (int) $request->get('department_id') : null,
            isActive: $request->has('is_active') ? filter_var($request->get('is_active'), FILTER_VALIDATE_BOOLEAN) : null,
            perPage: (int) $request->get('per_page', 15),
            sortBy: $request->get('sort_by', 'sort_order'),
            sortDir: $request->get('sort_dir', 'asc'),
        );

        return PersonnelResource::collection($personnel);
    }

    /**
     * GET /api/personnel/{id}
     * Show a single personnel record.
     */
    public function show(int $id): PersonnelResource
    {
        return new PersonnelResource($this->service->find($id));
    }

    /**
     * POST /api/personnel
     * Create a new personnel record.
     */
    public function store(StorePersonnelRequest $request): JsonResponse
    {
        $personnel = $this->service->create(
            $request->validated(),
            $request->file('photo')
        );

        return (new PersonnelResource($personnel))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * PUT /api/personnel/{id}
     * Update an existing personnel record.
     */
    public function update(UpdatePersonnelRequest $request, int $id): PersonnelResource
    {
        $personnel = $this->service->update(
            $id,
            $request->validated(),
            $request->file('photo')
        );

        return new PersonnelResource($personnel);
    }

    /**
     * DELETE /api/personnel/{id}
     * Soft delete a personnel record.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return response()->json(['message' => 'Personnel deleted successfully.'], 200);
    }

    /**
     * GET /api/personnel/statistics
     * Get personnel statistics for dashboard.
     */
    public function statistics(): JsonResponse
    {
        return response()->json($this->service->getStatistics());
    }
}
