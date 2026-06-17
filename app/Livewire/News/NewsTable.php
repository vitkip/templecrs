<?php

namespace App\Livewire\News;

use App\Models\News;
use App\Models\NewsCategory;
use App\Services\NewsService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class NewsTable extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $featuredFilter = '';

    #[Url]
    public string $categoryFilter = '';

    public string $sortBy  = 'published_at';
    public string $sortDir = 'desc';
    public int $perPage    = 15;

    public function updatedSearch(): void         { $this->resetPage(); }
    public function updatedStatusFilter(): void   { $this->resetPage(); }
    public function updatedFeaturedFilter(): void { $this->resetPage(); }
    public function updatedCategoryFilter(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->featuredFilter = '';
        $this->categoryFilter = '';
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
        app(NewsService::class)->toggleActive($id);
    }

    public function toggleFeatured(int $id): void
    {
        app(NewsService::class)->toggleFeatured($id);
    }

    public function deleteNews(int $id): void
    {
        app(NewsService::class)->delete($id);
        session()->flash('message', 'ລຶບຂ່າວສຳເລັດ / News deleted.');
    }

    public function render()
    {
        $isActive = match ($this->statusFilter) {
            'active'   => true,
            'inactive' => false,
            default    => null,
        };

        $isFeatured = match ($this->featuredFilter) {
            'featured'     => true,
            'not_featured' => false,
            default        => null,
        };

        $news = News::query()
            ->with(['author', 'category'])
            ->search($this->search ?: null)
            ->when($isActive !== null, fn ($q) => $q->where('is_active', $isActive))
            ->when($isFeatured !== null, fn ($q) => $q->where('is_featured', $isFeatured))
            ->when($this->categoryFilter !== '', function ($q) {
                if ($this->categoryFilter === 'null') {
                    $q->whereNull('news_category_id');
                } else {
                    $q->where('news_category_id', $this->categoryFilter);
                }
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $categories = NewsCategory::ordered()->get();
        $stats      = app(NewsService::class)->getStatistics();

        return view('livewire.news.table', [
            'newsList'   => $news,
            'stats'      => $stats,
            'categories' => $categories,
        ]);
    }
}
