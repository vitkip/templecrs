<?php

namespace App\Livewire\News;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CategoryManager extends Component
{
    // ── Modal state ──────────────────────────
    public bool $showModal       = false;
    public ?int $editingId       = null;
    public ?int $confirmDeleteId = null;

    // ── Form fields ──────────────────────────
    public string $slug      = '';
    public string $name_lo   = '';
    public string $name_en   = '';
    public string $icon      = 'newspaper';
    public string $color     = 'blue';
    public int    $sort_order = 0;
    public bool   $is_active  = true;

    // ── Options ──────────────────────────────
    public array $iconOptions = [
        'newspaper','breaking_news','feed','article','campaign','announcement',
        'record_voice_over','public','language','travel_explore','explore',
        'sports','sports_soccer','sports_basketball','emoji_events','military_tech',
        'church','spa','volunteer_activism','favorite','handshake','group',
        'school','science','biotech','health_and_safety','local_hospital',
        'business_center','apartment','factory','agriculture','eco',
        'photo_camera','movie','music_note','theater_comedy','palette',
    ];

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageNews(), 403);
    }

    protected function rules(): array
    {
        $slugUnique = $this->editingId
            ? Rule::unique('news_categories', 'slug')->ignore($this->editingId)
            : Rule::unique('news_categories', 'slug');

        return [
            'slug'       => ['required', 'string', 'max:60', 'regex:/^[a-z0-9_-]+$/', $slugUnique],
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
            'slug.required'    => 'ກະລຸນາໃສ່ Slug / Please enter a slug',
            'slug.unique'      => 'Slug ນີ້ຖືກໃຊ້ແລ້ວ / This slug is already taken',
            'slug.regex'       => 'Slug ຕ້ອງເປັນຕົວພິມນ້ອຍ, ຕົວເລກ ຫຼື - _ ເທົ່ານັ້ນ',
            'name_lo.required' => 'ກະລຸນາໃສ່ຊື່ໝວດ (ພາສາລາວ)',
            'name_lo.min'      => 'ຊື່ໝວດຕ້ອງມີຢ່າງໜ້ອຍ 2 ຕົວອັກສອນ',
        ];
    }

    public function updatedNameLo(string $value): void
    {
        if (!$this->editingId) {
            $this->slug = \Illuminate\Support\Str::slug($value, '-');
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $cat             = NewsCategory::findOrFail($id);
        $this->editingId = $id;
        $this->slug      = $cat->slug;
        $this->name_lo   = $cat->name_lo;
        $this->name_en   = $cat->name_en ?? '';
        $this->icon      = $cat->icon;
        $this->color     = $cat->color;
        $this->sort_order = $cat->sort_order;
        $this->is_active = $cat->is_active;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'slug'       => $this->slug,
            'name_lo'    => trim($this->name_lo),
            'name_en'    => trim($this->name_en) ?: null,
            'icon'       => $this->icon,
            'color'      => $this->color,
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
        ];

        if ($this->editingId) {
            NewsCategory::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'ແກ້ໄຂໝວດຂ່າວສຳເລັດ / Category updated.');
        } else {
            NewsCategory::create($data);
            session()->flash('message', 'ເພີ່ມໝວດຂ່າວສຳເລັດ / Category created.');
        }

        $this->closeModal();
    }

    public function toggleActive(int $id): void
    {
        $cat = NewsCategory::findOrFail($id);
        $cat->update(['is_active' => !$cat->is_active]);
    }

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

        $cat = NewsCategory::find($this->confirmDeleteId);
        if ($cat) {
            $newsCount = News::where('news_category_id', $cat->id)->count();
            if ($newsCount > 0) {
                session()->flash('error', "ບໍ່ສາມາດລຶບໄດ້ — ມີຂ່າວ {$newsCount} ລາຍການໃຊ້ໝວດນີ້ / Cannot delete — {$newsCount} article(s) use this category.");
                $this->confirmDeleteId = null;
                return;
            }
            $cat->delete();
            session()->flash('message', 'ລຶບໝວດຂ່າວສຳເລັດ / Category deleted.');
        }

        $this->confirmDeleteId = null;
    }

    private function resetForm(): void
    {
        $this->editingId  = null;
        $this->slug       = '';
        $this->name_lo    = '';
        $this->name_en    = '';
        $this->icon       = 'newspaper';
        $this->color      = 'blue';
        $this->sort_order = 0;
        $this->is_active  = true;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = NewsCategory::withCount(['news as news_count'])
            ->orderBy('sort_order')
            ->orderBy('name_lo')
            ->get();

        return view('livewire.news.category-manager', compact('categories'))
            ->layout('components.layouts.app', ['title' => 'ໝວດຂ່າວ / News Categories']);
    }
}
