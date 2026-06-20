<?php

namespace App\Livewire\Documents;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\DocumentService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentTable extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $departmentFilter = '';

    #[Url]
    public string $statusFilter = '';

    public string $sortBy  = 'issued_date';
    public string $sortDir = 'desc';
    public int $perPage    = 15;

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageDocuments(), 403);
    }

    public function updatedSearch(): void      { $this->resetPage(); }
    public function updatedCategory(): void    { $this->resetPage(); }
    public function updatedDepartmentFilter(): void { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->category = '';
        $this->departmentFilter = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'desc';
        }
    }

    public function toggleActive(int $id): void
    {
        app(DocumentService::class)->toggleActive($id);
    }

    public function deleteDocument(int $id): void
    {
        app(DocumentService::class)->delete($id);
        session()->flash('message', 'ລຶບເອກະສານສຳເລັດ / Document deleted.');
    }

    public function render()
    {
        $isActive = match ($this->statusFilter) {
            'active'   => true,
            'inactive' => false,
            default    => null,
        };

        $documents = Document::query()
            ->with(['department', 'uploader'])
            ->search($this->search ?: null)
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->when($this->departmentFilter, fn ($q) => $q->where('department_id', $this->departmentFilter))
            ->when($isActive !== null, fn ($q) => $q->where('is_active', $isActive))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $stats       = app(DocumentService::class)->getStatistics();
        $departments = Department::active()->ordered()->get(['id', 'name_lo', 'name_en']);
        $categories  = DocumentCategory::active()->ordered()->get();

        return view('livewire.documents.table', [
            'documents'   => $documents,
            'stats'       => $stats,
            'departments' => $departments,
            'categories'  => $categories,
        ]);
    }
}
