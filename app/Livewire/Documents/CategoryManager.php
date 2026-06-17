<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentCategory;
use Livewire\Component;

class CategoryManager extends Component
{
    // ── Modal state ──────────────────────────
    public bool $showModal       = false;
    public ?int $editingId       = null;
    public ?int $confirmDeleteId = null;

    // ── Form fields ──────────────────────────
    public string $slug     = '';
    public string $name_lo  = '';
    public string $name_en  = '';
    public string $icon     = 'description';
    public string $color    = 'gray';
    public int    $sort_order = 0;
    public bool   $is_active = true;

    // ── Options ──────────────────────────────
    public array $iconOptions = [
        'description','gavel','campaign','workspace_premium','assessment','folder_special',
        'article','receipt_long','inventory_2','library_books','picture_as_pdf','badge',
        'task','assignment','file_present','how_to_reg','approval','policy',
        'menu_book','book','bookmark','label','local_offer','star',
        'church','spa','water_drop','volunteer_activism','favorite','handshake',
    ];

    protected function rules(): array
    {
        $slugUnique = $this->editingId
            ? \Illuminate\Validation\Rule::unique('document_categories', 'slug')->ignore($this->editingId)
            : \Illuminate\Validation\Rule::unique('document_categories', 'slug');

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

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $cat             = DocumentCategory::findOrFail($id);
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
            DocumentCategory::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'ແກ້ໄຂໝວດເອກະສານສຳເລັດ / Category updated.');
        } else {
            DocumentCategory::create($data);
            session()->flash('message', 'ເພີ່ມໝວດເອກະສານສຳເລັດ / Category created.');
        }

        $this->closeModal();
    }

    public function toggleActive(int $id): void
    {
        $cat = DocumentCategory::findOrFail($id);
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

        $cat = DocumentCategory::find($this->confirmDeleteId);
        if ($cat) {
            $docCount = Document::where('category', $cat->slug)->count();
            if ($docCount > 0) {
                session()->flash('error', "ບໍ່ສາມາດລຶບໄດ້ — ມີເອກະສານ {$docCount} ລາຍການໃຊ້ໝວດນີ້ / Cannot delete — {$docCount} document(s) use this category.");
                $this->confirmDeleteId = null;
                return;
            }
            $cat->delete();
            session()->flash('message', 'ລຶບໝວດເອກະສານສຳເລັດ / Category deleted.');
        }

        $this->confirmDeleteId = null;
    }


    private function resetForm(): void
    {
        $this->editingId  = null;
        $this->slug       = '';
        $this->name_lo    = '';
        $this->name_en    = '';
        $this->icon       = 'description';
        $this->color      = 'gray';
        $this->sort_order = 0;
        $this->is_active  = true;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = DocumentCategory::withCount([
            'documents as doc_count',
        ])
        ->orderBy('sort_order')
        ->orderBy('name_lo')
        ->get();

        return view('livewire.documents.category-manager', compact('categories'))
            ->layout('components.layouts.app', ['title' => 'ໝວດເອກະສານ / Document Categories']);
    }
}
