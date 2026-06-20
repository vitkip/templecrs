<?php

namespace App\Livewire\Personnel;

use App\Models\Department;
use App\Models\Personnel;
use App\Services\PersonnelService;
use Livewire\Attributes\Url;
use Livewire\Component;

class PersonnelTable extends Component
{
    #[Url]
    public string $gender = '';

    #[Url]
    public string $departmentFilter = '';

    #[Url]
    public string $statusFilter = 'active';

    public function clearFilters(): void
    {
        $this->gender = '';
        $this->departmentFilter = '';
        $this->statusFilter = 'active';
    }

    public function toggleActive(int $id): void
    {
        app(PersonnelService::class)->toggleActive($id);
    }

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
            ->when($this->gender, fn ($q) => $q->where('gender', $this->gender))
            ->when($this->departmentFilter, fn ($q) => $q->where('department_id', $this->departmentFilter))
            ->when($isActive !== null, fn ($q) => $q->where('is_active', $isActive))
            ->ordered()
            ->get();

        $stats       = app(PersonnelService::class)->getStatistics();
        $departments = Department::active()->ordered()->get(['id', 'name_lo', 'name_en']);

        return view('livewire.personnel.table', [
            'personnel'   => $personnel,
            'stats'       => $stats,
            'departments' => $departments,
        ]);
    }
}
