<?php

namespace App\Livewire\Personnel;

use App\Models\Department;
use App\Models\Personnel;
use App\Services\PersonnelService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PersonnelTable extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $gender = '';

    #[Url]
    public string $departmentFilter = '';

    #[Url]
    public string $statusFilter = 'active';

    public string $sortBy = 'sort_order';
    public string $sortDir = 'asc';
    public int $perPage = 15;

    /**
     * Reset pagination when filters change.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedGender(): void
    {
        $this->resetPage();
    }

    public function updatedDepartmentFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Clear all filters.
     */
    public function clearFilters(): void
    {
        $this->search = '';
        $this->gender = '';
        $this->departmentFilter = '';
        $this->statusFilter = 'active';
        $this->resetPage();
    }

    /**
     * Sort by column.
     */
    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    /**
     * Toggle active status for a personnel record.
     */
    public function toggleActive(int $id): void
    {
        app(PersonnelService::class)->toggleActive($id);
    }

    /**
     * Delete a personnel record.
     */
    public function deletePersonnel(int $id): void
    {
        app(PersonnelService::class)->delete($id);
        session()->flash('message', 'ລຶບສຳເລັດແລ້ວ / Deleted successfully.');
    }

    public function render()
    {
        $isActive = match ($this->statusFilter) {
            'active'   => true,
            'inactive' => false,
            default    => null,
        };

        $personnel = Personnel::query()
            ->with('department')
            ->search($this->search ?: null)
            ->when($this->gender, fn ($q) => $q->where('gender', $this->gender))
            ->when($this->departmentFilter, fn ($q) => $q->where('department_id', $this->departmentFilter))
            ->when($isActive !== null, fn ($q) => $q->where('is_active', $isActive))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $stats = app(PersonnelService::class)->getStatistics();
        $departments = Department::active()->ordered()->get(['id', 'name_lo', 'name_en']);

        return view('livewire.personnel.table', [
            'personnel'   => $personnel,
            'stats'       => $stats,
            'departments' => $departments,
        ]);
    }
}
