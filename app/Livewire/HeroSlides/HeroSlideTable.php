<?php

namespace App\Livewire\HeroSlides;

use App\Models\HeroSlide;
use App\Services\HeroSlideService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class HeroSlideTable extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    public string $sortBy  = 'sort_order';
    public string $sortDir = 'asc';
    public int $perPage    = 10;

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);
    }

    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy  = $column;
            $this->sortDir = 'asc';
        }
    }

    public function toggleActive(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        app(HeroSlideService::class)->toggleActive($id);
    }

    public function deleteSlide(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->isAdmin(), 403);

        app(HeroSlideService::class)->delete($id);
        session()->flash('message', 'ລຶບສະໄລ້ສຳເລັດ / Slide deleted.');
    }

    public function render()
    {
        $isActive = match ($this->statusFilter) {
            'active'   => true,
            'inactive' => false,
            default    => null,
        };

        $slides = HeroSlide::query()
            ->search($this->search ?: null)
            ->when($isActive !== null, fn ($q) => $q->where('is_active', $isActive))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $stats = app(HeroSlideService::class)->getStatistics();

        return view('livewire.hero-slides.table', [
            'slides' => $slides,
            'stats'  => $stats,
        ]);
    }
}
