<?php

namespace App\Services;

use App\Models\Personnel;
use App\Repositories\PersonnelRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PersonnelService
{
    public function __construct(
        protected PersonnelRepository $repository
    ) {}

    /**
     * Get paginated personnel list with filters.
     */
    public function list(
        ?string $search = null,
        ?string $gender = null,
        ?int $departmentId = null,
        ?bool $isActive = null,
        int $perPage = 15,
        string $sortBy = 'sort_order',
        string $sortDir = 'asc'
    ): LengthAwarePaginator {
        return $this->repository->getAll($search, $gender, $departmentId, $isActive, $perPage, $sortBy, $sortDir);
    }

    /**
     * Get a single personnel by ID.
     */
    public function find(int $id): Personnel
    {
        return $this->repository->getById($id);
    }

    /**
     * Create a new personnel record.
     */
    public function create(array $data, ?UploadedFile $photo = null): Personnel
    {
        // Auto-generate full names if components provided
        $data = $this->autoGenerateNames($data);

        // Handle photo upload
        if ($photo) {
            $data['photo_url'] = $this->uploadPhoto($photo);
        }

        // Clean monk-specific fields for non-monks
        if (($data['gender'] ?? '') !== 'monk') {
            $data = $this->clearMonkFields($data);
        }

        return $this->repository->create($data);
    }

    /**
     * Update an existing personnel record.
     */
    public function update(int $id, array $data, ?UploadedFile $photo = null): Personnel
    {
        // Auto-generate full names if components provided
        $data = $this->autoGenerateNames($data);

        // Handle photo upload
        if ($photo) {
            // Delete old photo
            $old = $this->repository->getById($id);
            if ($old->photo_url) {
                Storage::disk('public')->delete($old->photo_url);
            }
            $data['photo_url'] = $this->uploadPhoto($photo);
        }

        // Clean monk-specific fields for non-monks
        if (($data['gender'] ?? '') !== 'monk') {
            $data = $this->clearMonkFields($data);
        }

        return $this->repository->update($id, $data);
    }

    /**
     * Delete a personnel record.
     */
    public function delete(int $id): bool
    {
        $personnel = $this->repository->getById($id);
        if ($personnel->photo_url) {
            Storage::disk('public')->delete($personnel->photo_url);
        }
        return $this->repository->delete($id);
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(int $id): Personnel
    {
        return $this->repository->toggleActive($id);
    }

    /**
     * Get dashboard statistics.
     */
    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }

    /* ───── Private Helpers ───── */

    /**
     * Auto-generate name_lo and name_en from title + first + last.
     */
    private function autoGenerateNames(array $data): array
    {
        // Only auto-generate if name_lo is empty but components exist
        if (empty($data['name_lo']) && (!empty($data['first_name_lo']) || !empty($data['last_name_lo']))) {
            $data['name_lo'] = Personnel::buildFullName(
                $data['title_lo'] ?? null,
                $data['first_name_lo'] ?? null,
                $data['last_name_lo'] ?? null
            );
        }

        if (empty($data['name_en']) && (!empty($data['first_name_en']) || !empty($data['last_name_en']))) {
            $data['name_en'] = Personnel::buildFullName(
                $data['title_en'] ?? null,
                $data['first_name_en'] ?? null,
                $data['last_name_en'] ?? null
            );
        }

        return $data;
    }

    /**
     * Upload and resize photo.
     */
    private function uploadPhoto(UploadedFile $photo): string
    {
        return $photo->store('personnel/photos', 'public');
    }

    /**
     * Clear monk-specific fields for non-monks.
     */
    private function clearMonkFields(array $data): array
    {
        $data['current_temple_lo'] = null;
        $data['current_temple_en'] = null;
        $data['date_of_ordination'] = null;
        $data['pansa'] = null;
        return $data;
    }
}
