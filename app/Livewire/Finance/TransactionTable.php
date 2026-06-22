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
    public string $currencyFilter = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    public string $sortBy  = 'transaction_date';
    public string $sortDir = 'desc';
    public int $perPage    = 20;

    public ?int $confirmDeleteId = null;

    private const ALLOWED_SORT = ['transaction_date', 'amount'];

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageFinance(), 403);
    }

    public function updatedSearch(): void         { $this->resetPage(); }
    public function updatedTypeFilter(): void     { $this->resetPage(); }
    public function updatedCategoryFilter(): void { $this->resetPage(); }
    public function updatedCurrencyFilter(): void { $this->resetPage(); }
    public function updatedDateFrom(): void       { $this->resetPage(); }
    public function updatedDateTo(): void         { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = $this->typeFilter = $this->categoryFilter
                      = $this->currencyFilter = $this->dateFrom = $this->dateTo = '';
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if (!in_array($column, self::ALLOWED_SORT, true)) return;
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

    /** Shared filter constraints applied to every query in render(). */
    private function applyFilters($query)
    {
        return $query
            ->when($this->typeFilter,     fn($q) => $q->where('type',        $this->typeFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->when($this->currencyFilter, fn($q) => $q->where('currency',    $this->currencyFilter))
            ->when($this->search,         fn($q) => $q->search($this->search))
            ->dateBetween($this->dateFrom ?: null, $this->dateTo ?: null);
    }

    public function render()
    {
        $transactions = $this->applyFilters(
            FinanceTransaction::with('category', 'creator')
        )->orderBy($this->sortBy, $this->sortDir)
         ->paginate($this->perPage);

        $categories = FinanceCategory::active()->ordered()->get();
        $currencies = FinanceTransaction::CURRENCIES;

        // Totals per currency — amounts are NOT converted, so we group by currency
        $totals = $this->applyFilters(
            FinanceTransaction::selectRaw("currency, type, SUM(amount) as total")
        )->groupBy('currency', 'type')
         ->get();

        // Build [currency => [income => X, expense => Y]] map for the view
        $byCurrency = [];
        foreach (array_keys($currencies) as $code) {
            $inc = $totals->where('type', 'income') ->where('currency', $code)->first();
            $exp = $totals->where('type', 'expense')->where('currency', $code)->first();
            if ($inc || $exp) {
                $byCurrency[$code] = [
                    'income'  => (float) ($inc->total ?? 0),
                    'expense' => (float) ($exp->total ?? 0),
                ];
            }
        }

        return view('livewire.finance.transaction-table',
            compact('transactions', 'categories', 'currencies', 'byCurrency'))
            ->layout('components.layouts.app', ['title' => __('messages.finance')]);
    }
}
