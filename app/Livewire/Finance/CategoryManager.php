<?php

namespace App\Livewire\Finance;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Livewire\Attributes\Url;
use Livewire\Component;

class CategoryManager extends Component
{
    // ── Filters ──────────────────────────────
    #[Url]
    public string $typeFilter = '';

    // ── Modal state ──────────────────────────
    public bool   $showModal      = false;
    public ?int   $editingId      = null;
    public ?int   $confirmDeleteId = null;

    // ── Form fields ──────────────────────────
    public string $type       = 'income';
    public string $name_lo    = '';
    public string $name_en    = '';
    public string $icon       = 'category';
    public string $color      = 'blue';
    public int    $sort_order  = 0;
    public bool   $is_active  = true;

    // ── Icon/colour options ───────────────────
    public array $iconOptions = [
        'category','volunteer_activism','favorite','event','school','handshake',
        'payments','savings','add_circle','more_horiz','bolt','build','restaurant',
        'shopping_cart','directions_car','celebration','receipt','attach_money',
        'account_balance','church','spa','local_florist','water_drop','medical_services',
    ];

    public array $colorOptions = [
        'green','emerald','teal','cyan','blue','indigo','violet','purple',
        'pink','rose','red','orange','yellow','amber','slate',
    ];

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageFinance(), 403);
    }

    protected function rules(): array
    {
        return [
            'type'       => ['required', 'in:income,expense'],
            'name_lo'    => ['required', 'string', 'min:2', 'max:200'],
            'name_en'    => ['nullable', 'string', 'max:200'],
            'icon'       => ['required', 'string', 'max:60'],
            'color'      => ['required', 'string', 'max:20'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999'],
            'is_active'  => ['boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'name_lo.required' => __('messages.validation_required'),
            'name_lo.min'      => __('messages.validation_min_length'),
            'type.required'    => __('messages.validation_required'),
        ];
    }

    // ── Open modal for create ─────────────────
    public function create(): void
    {
        $this->resetForm();
        $this->type      = $this->typeFilter ?: 'income';
        $this->showModal = true;
    }

    // ── Open modal for edit ───────────────────
    public function edit(int $id): void
    {
        $cat = FinanceCategory::findOrFail($id);
        $this->editingId   = $id;
        $this->type        = $cat->type;
        $this->name_lo     = $cat->name_lo;
        $this->name_en     = $cat->name_en ?? '';
        $this->icon        = $cat->icon;
        $this->color       = $cat->color;
        $this->sort_order  = $cat->sort_order;
        $this->is_active   = $cat->is_active;
        $this->showModal   = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // ── Save (create or update) ───────────────
    public function save(): void
    {
        $this->validate();

        $data = [
            'type'       => $this->type,
            'name_lo'    => trim($this->name_lo),
            'name_en'    => trim($this->name_en) ?: null,
            'icon'       => $this->icon,
            'color'      => $this->color,
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
        ];

        if ($this->editingId) {
            FinanceCategory::findOrFail($this->editingId)->update($data);
            session()->flash('message', __('messages.updated_successfully'));
        } else {
            FinanceCategory::create($data);
            session()->flash('message', __('messages.created_successfully'));
        }

        $this->closeModal();
    }

    // ── Toggle active status ──────────────────
    public function toggleActive(int $id): void
    {
        $cat = FinanceCategory::findOrFail($id);
        $cat->update(['is_active' => !$cat->is_active]);
    }

    // ── Delete confirm ────────────────────────
    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmDeleteId = null;
    }

    public function delete(): void
    {
        if (!$this->confirmDeleteId) return;

        $cat = FinanceCategory::find($this->confirmDeleteId);
        if ($cat) {
            $txCount = FinanceTransaction::where('category_id', $cat->id)->count();
            if ($txCount > 0) {
                session()->flash('error', __('messages.category_has_transactions', ['count' => $txCount]));
                $this->confirmDeleteId = null;
                return;
            }
            $cat->delete();
            session()->flash('message', __('messages.deleted_successfully'));
        }

        $this->confirmDeleteId = null;
    }

    private function resetForm(): void
    {
        $this->editingId  = null;
        $this->type       = 'income';
        $this->name_lo    = '';
        $this->name_en    = '';
        $this->icon       = 'category';
        $this->color      = 'blue';
        $this->sort_order = 0;
        $this->is_active  = true;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = FinanceCategory::query()
            ->when($this->typeFilter, fn($q) => $q->where('type', $this->typeFilter))
            ->withCount('transactions')
            ->orderBy('type')
            ->orderBy('sort_order')
            ->orderBy('name_lo')
            ->get()
            ->groupBy('type');

        $totalIncome  = FinanceCategory::where('type', 'income')->count();
        $totalExpense = FinanceCategory::where('type', 'expense')->count();

        return view('livewire.finance.category-manager', compact('categories', 'totalIncome', 'totalExpense'))
            ->layout('components.layouts.app', ['title' => __('messages.finance_categories')]);
    }
}
