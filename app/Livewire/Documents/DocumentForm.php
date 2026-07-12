<?php

namespace App\Livewire\Documents;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\DocumentService;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentForm extends Component
{
    use WithFileUploads;

    public bool $editMode    = false;
    public ?int $documentId  = null;

    // Identity
    public string $title_lo  = '';
    public ?string $title_en = null;
    public ?string $doc_number = null;
    public string $category  = 'other';

    // Description
    public ?string $description_lo = null;
    public ?string $description_en = null;

    // Relations
    public ?int $department_id = null;

    // Dates
    public ?string $issued_date = null;

    // File
    public $file = null;
    public ?string $existing_file_name = null;
    public ?string $existing_file_path = null;

    // Cover Image
    public $cover_image = null;
    public ?string $existing_cover_image = null;

    // Display
    public int $sort_order  = 0;
    public bool $is_active  = true;

    public function mount(?int $id = null): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageDocuments(), 403);

        if ($id) {
            $this->editMode   = true;
            $this->documentId = $id;
            $this->loadDocument($id);
        }
    }

    private function loadDocument(int $id): void
    {
        $doc = Document::findOrFail($id);

        $this->title_lo        = $doc->title_lo;
        $this->title_en        = $doc->title_en;
        $this->doc_number      = $doc->doc_number;
        $this->category        = $doc->category;
        $this->description_lo  = $doc->description_lo;
        $this->description_en  = $doc->description_en;
        $this->department_id   = $doc->department_id;
        $this->issued_date     = $doc->issued_date?->format('Y-m-d');
        $this->sort_order      = $doc->sort_order ?? 0;
        $this->is_active       = $doc->is_active ?? true;
        $this->existing_file_name = $doc->file_name;
        $this->existing_file_path = $doc->file_path;
        $this->existing_cover_image = $doc->cover_image;
    }

    protected function rules(): array
    {
        return [
            'title_lo'       => 'required|string|max:300',
            'title_en'       => 'nullable|string|max:300',
            'doc_number'     => 'nullable|string|max:100',
            'category'       => 'required|exists:document_categories,slug',
            'description_lo' => 'nullable|string',
            'description_en' => 'nullable|string',
            'department_id'  => 'nullable|exists:departments,id',
            'issued_date'    => 'nullable|date',
            'file'           => ($this->editMode ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp|max:512000',
            'cover_image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'title_lo.required' => 'ກະລຸນາໃສ່ຊື່ເອກະສານ (ພາສາລາວ)',
            'category.required' => 'ກະລຸນາເລືອກໝວດເອກະສານ',
            'file.required'     => 'ກະລຸນາເລືອກໄຟລ໌ເອກະສານ',
            'file.max'          => 'ໄຟລ໌ຕ້ອງບໍ່ເກີນ 500MB',
            'file.mimes'        => 'ຮູບແບບໄຟລ໌ທີ່ຮອງຮັບ: PDF, Word, Excel, JPG, PNG',
            'cover_image.image' => 'ກະລຸນາເລືອກໄຟລ໌ຮູບພາບສຳລັບໜ້າປົກ',
            'cover_image.max'   => 'ຮູບໜ້າປົກຕ້ອງບໍ່ເກີນ 10MB',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $service = app(DocumentService::class);

        $data = [
            'title_lo'       => $this->title_lo,
            'title_en'       => $this->title_en,
            'doc_number'     => $this->doc_number,
            'category'       => $this->category,
            'description_lo' => $this->description_lo,
            'description_en' => $this->description_en,
            'department_id'  => $this->department_id,
            'issued_date'    => $this->issued_date ?: null,
            'sort_order'     => $this->sort_order,
            'is_active'      => $this->is_active,
        ];

        if ($this->editMode) {
            $service->update($this->documentId, $data, $this->file, $this->cover_image);
            session()->flash('message', 'ແກ້ໄຂເອກະສານສຳເລັດ / Document updated.');
        } else {
            $service->create($data, $this->file, $this->cover_image);
            session()->flash('message', 'ອັບໂຫລດເອກະສານສຳເລັດ / Document uploaded.');
        }

        $this->redirect(route('documents.index'), navigate: true);
    }

    public function render()
    {
        $departments = Department::active()->ordered()->get(['id', 'name_lo', 'name_en']);
        $categories  = DocumentCategory::active()->ordered()->get();

        return view('livewire.documents.form', [
            'departments' => $departments,
            'categories'  => $categories,
        ]);
    }
}
