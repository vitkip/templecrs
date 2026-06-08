<?php

namespace App\Livewire\Finance;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionTable extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $typeFilter = '';

    #[Url]
    public string $categoryFilter = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    public string $sortBy  = 'transaction_date';
    public string $sortDir = 'desc';
    public int $perPage    = 20;

    public ?int $confirmDeleteId = null;

    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedTypeFilter(): void   { $this->resetPage(); }
    public function updatedCategoryFilter(): void { $this->resetPage(); }
    public function updatedDateFrom(): void     { $this->resetPage(); }
    public function updatedDateTo(): void       { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = $this->typeFilter = $this->categoryFilter = $this->dateFrom = $this->dateTo = '';
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortBy  = $column;
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function delete(): void
    {
        if (!$this->confirmDeleteId) return;

        $tx = FinanceTransaction::find($this->confirmDeleteId);
        if ($tx) {
            $tx->delete();
            session()->flash('message', __('messages.deleted_successfully'));
        }

        $this->confirmDeleteId = null;
        $this->resetPage();
    }

    public function cancelDelete(): void
    {
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $transactions = FinanceTransaction::with('category', 'creator')
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->when($this->search, fn($q) => $q->search($this->search))
            ->dateBetween($this->dateFrom ?: null, $this->dateTo ?: null)
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $categories = FinanceCategory::active()->ordered()->get();

        $totals = FinanceTransaction::selectRaw("
            SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense
        ")->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
          ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
          ->when($this->search, fn($q) => $q->search($this->search))
          ->dateBetween($this->dateFrom ?: null, $this->dateTo ?: null)
          ->first();

        return view('livewire.finance.transaction-table', compact('transactions', 'categories', 'totals'))
            ->layout('components.layouts.app', ['title' => __('messages.finance')]);
    }
}
