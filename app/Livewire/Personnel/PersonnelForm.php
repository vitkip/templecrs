<?php

namespace App\Livewire\Personnel;

use App\Models\Department;
use App\Models\Personnel;
use App\Services\PersonnelService;
use Livewire\Component;
use Livewire\WithFileUploads;

class PersonnelForm extends Component
{
    use WithFileUploads;

    // Mode: create or edit
    public bool $editMode = false;
    public ?int $personnelId = null;

    // Person Type
    public string $gender = 'male';

    // Name — Bilingual
    public string $first_name_lo = '';
    public ?string $first_name_en = null;
    public string $last_name_lo = '';
    public ?string $last_name_en = null;
    public string $name_lo = '';
    public ?string $name_en = null;

    // Title / Honorific
    public ?string $title_lo = null;
    public ?string $title_en = null;

    // Position
    public string $position_lo = '';
    public ?string $position_en = null;

    // Department
    public ?int $department_id = null;

    // Location
    public ?string $birth_village_lo = null;
    public ?string $birth_village_en = null;
    public ?string $district_lo = null;
    public ?string $district_en = null;
    public ?string $province_lo = null;
    public ?string $province_en = null;

    // Monk-only
    public ?string $current_temple_lo = null;
    public ?string $current_temple_en = null;
    public ?string $date_of_ordination = null;
    public ?int $pansa = null;
    public ?int $pansaAutoCalc = null; // calculated value shown as hint

    // Contact
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $facebook = null;
    public $photo = null;
    public ?string $existing_photo_url = null;

    // Bio & Education
    public ?string $bio_lo = null;
    public ?string $bio_en = null;
    public ?string $education_lo = null;
    public ?string $education_en = null;

    // Personal Info
    public ?string $date_of_birth = null;

    // Term
    public ?string $term_start = null;
    public ?string $term_end = null;

    // Display Control
    public int $sort_order = 0;
    public bool $is_active = true;

    /**
     * Mount component — load existing data for edit mode.
     */
    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->editMode = true;
            $this->personnelId = $id;
            $this->loadPersonnel($id);
        }
        // Always compute hint after loading
        $this->pansaAutoCalc = $this->calculatePansa();
    }

    /**
     * Load existing personnel data into form fields.
     */
    private function loadPersonnel(int $id): void
    {
        $p = Personnel::findOrFail($id);

        $this->gender = $p->gender ?? 'male';
        $this->first_name_lo = $p->first_name_lo ?? '';
        $this->first_name_en = $p->first_name_en;
        $this->last_name_lo = $p->last_name_lo ?? '';
        $this->last_name_en = $p->last_name_en;
        $this->name_lo = $p->name_lo ?? '';
        $this->name_en = $p->name_en;
        $this->title_lo = $p->title_lo;
        $this->title_en = $p->title_en;
        $this->position_lo = $p->position_lo ?? '';
        $this->position_en = $p->position_en;
        $this->department_id = $p->department_id;
        $this->birth_village_lo = $p->birth_village_lo;
        $this->birth_village_en = $p->birth_village_en;
        $this->district_lo = $p->district_lo;
        $this->district_en = $p->district_en;
        $this->province_lo = $p->province_lo;
        $this->province_en = $p->province_en;
        $this->current_temple_lo = $p->current_temple_lo;
        $this->current_temple_en = $p->current_temple_en;
        $this->date_of_ordination = $p->date_of_ordination?->format('Y-m-d');
        $this->pansa = $p->pansa;
        $this->email = $p->email;
        $this->phone = $p->phone;
        $this->facebook = $p->facebook;
        $this->existing_photo_url = $p->photo_url;
        $this->bio_lo = $p->bio_lo;
        $this->bio_en = $p->bio_en;
        $this->education_lo = $p->education_lo;
        $this->education_en = $p->education_en;
        $this->date_of_birth = $p->date_of_birth?->format('Y-m-d');
        $this->term_start = $p->term_start;
        $this->term_end = $p->term_end;
        $this->sort_order = $p->sort_order ?? 0;
        $this->is_active = $p->is_active ?? true;
    }

    /**
     * Validation rules.
     */
    protected function rules(): array
    {
        $rules = [
            'gender'      => 'required|in:monk,male,female',
            'name_lo'     => 'required|string|max:200',
            'name_en'     => 'nullable|string|max:200',
            'position_lo' => 'required|string|max:200',
            'position_en' => 'nullable|string|max:200',
            'department_id' => 'nullable|exists:departments,id',
            'first_name_lo' => 'nullable|string|max:100',
            'first_name_en' => 'nullable|string|max:100',
            'last_name_lo'  => 'nullable|string|max:100',
            'last_name_en'  => 'nullable|string|max:100',
            'title_lo'     => 'nullable|string|max:100',
            'title_en'     => 'nullable|string|max:100',
            'birth_village_lo' => 'nullable|string|max:200',
            'birth_village_en' => 'nullable|string|max:200',
            'district_lo'     => 'nullable|string|max:100',
            'district_en'     => 'nullable|string|max:100',
            'province_lo'     => 'nullable|string|max:100',
            'province_en'     => 'nullable|string|max:100',
            'email'    => 'nullable|email|max:120',
            'phone'    => 'nullable|string|max:50',
            'facebook' => 'nullable|max:300',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'bio_lo'       => 'nullable|string',
            'bio_en'       => 'nullable|string',
            'education_lo' => 'nullable|string|max:300',
            'education_en' => 'nullable|string|max:300',
            'date_of_birth' => 'nullable|date|before:today',
            'term_start' => 'nullable|digits:4',
            'term_end'   => 'nullable|digits:4',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'boolean',
        ];

        if ($this->gender === 'monk') {
            $rules['current_temple_lo']  = 'nullable|string|max:300';
            $rules['current_temple_en']  = 'nullable|string|max:300';
            $rules['date_of_ordination'] = 'nullable|date|before_or_equal:today';
            $rules['pansa']              = 'nullable|integer|min:0|max:100';
        }

        return $rules;
    }

    /**
     * Custom validation messages.
     */
    protected function messages(): array
    {
        return [
            'name_lo.required'     => 'ກະລຸນາໃສ່ຊື່ເຕັມ (ພາສາລາວ)',
            'position_lo.required' => 'ກະລຸນາໃສ່ຕຳແໜ່ງ (ພາສາລາວ)',
            'gender.required'      => 'ກະລຸນາເລືອກປະເພດບຸກຄົນ',
        ];
    }

    /**
     * Auto-generate full name when components change.
     */
    public function updatedTitleLo(): void
    {
        $this->autoGenerateNameLo();
    }

    public function updatedFirstNameLo(): void
    {
        $this->autoGenerateNameLo();
    }

    public function updatedLastNameLo(): void
    {
        $this->autoGenerateNameLo();
    }

    public function updatedTitleEn(): void
    {
        $this->autoGenerateNameEn();
    }

    public function updatedFirstNameEn(): void
    {
        $this->autoGenerateNameEn();
    }

    public function updatedLastNameEn(): void
    {
        $this->autoGenerateNameEn();
    }

    /**
     * Auto-calculate pansa when ordination date changes.
     */
    public function updatedDateOfOrdination(): void
    {
        $this->pansaAutoCalc = $this->calculatePansa();
        // Auto-fill pansa if not manually set yet
        if ($this->pansaAutoCalc !== null) {
            $this->pansa = $this->pansaAutoCalc;
        }
    }

    /**
     * Calculate pansa (Vassa seniority) from ordination date.
     *
     * Rules (Theravada / Lao tradition):
     *  - Khao Phansa ≈ day after Asalha Puja  (~August 1)
     *  - Ok Phansa   ≈ full moon 11th month   (~October 16)
     *  - Ordained BEFORE Khao Phansa → first Pansa done at Ok Phansa of same year
     *  - Ordained AFTER  Khao Phansa → first Pansa done at Ok Phansa of next year
     */
    private function calculatePansa(): ?int
    {
        if (!$this->date_of_ordination) {
            return null;
        }

        try {
            $ordination = \Carbon\Carbon::parse($this->date_of_ordination)->startOfDay();
            $today      = \Carbon\Carbon::today();

            if ($ordination->isAfter($today)) {
                return 0;
            }

            // Approximate day-of-year thresholds
            $khaoPhansaDoy = 213; // ~August 1
            $okPhansaDoy   = 289; // ~October 16

            // Year in which monk's FIRST Vassa ends (first Pansa earned)
            $firstVassaEndYear = $ordination->year;
            if ($ordination->dayOfYear > $khaoPhansaDoy) {
                // Ordained after Khao Phansa → first Pansa next year
                $firstVassaEndYear++;
            }

            // Most recent completed Vassa year as of today
            $lastVassaEndYear = $today->year;
            if ($today->dayOfYear < $okPhansaDoy) {
                // Ok Phansa not yet reached → current year's Vassa not done
                $lastVassaEndYear--;
            }

            return max(0, $lastVassaEndYear - $firstVassaEndYear + 1);
        } catch (\Exception) {
            return null;
        }
    }

    private function autoGenerateNameLo(): void
    {
        $name = Personnel::buildFullName($this->title_lo, $this->first_name_lo, $this->last_name_lo);
        if (!empty($name)) {
            $this->name_lo = $name;
        }
    }

    private function autoGenerateNameEn(): void
    {
        $name = Personnel::buildFullName($this->title_en, $this->first_name_en, $this->last_name_en);
        if (!empty($name)) {
            $this->name_en = $name;
        }
    }

    /**
     * Save the personnel record.
     */
    public function save(): void
    {
        $this->validate();

        $service = app(PersonnelService::class);

        $data = [
            'gender' => $this->gender,
            'first_name_lo' => $this->first_name_lo ?: null,
            'first_name_en' => $this->first_name_en,
            'last_name_lo' => $this->last_name_lo ?: null,
            'last_name_en' => $this->last_name_en,
            'name_lo' => $this->name_lo,
            'name_en' => $this->name_en,
            'title_lo' => $this->title_lo,
            'title_en' => $this->title_en,
            'position_lo' => $this->position_lo,
            'position_en' => $this->position_en,
            'department_id' => $this->department_id,
            'birth_village_lo' => $this->birth_village_lo,
            'birth_village_en' => $this->birth_village_en,
            'district_lo' => $this->district_lo,
            'district_en' => $this->district_en,
            'province_lo' => $this->province_lo,
            'province_en' => $this->province_en,
            'current_temple_lo' => $this->current_temple_lo,
            'current_temple_en' => $this->current_temple_en,
            'date_of_ordination' => $this->date_of_ordination ?: null,
            'pansa' => $this->pansa,
            'email' => $this->email,
            'phone' => $this->phone,
            'facebook' => $this->facebook,
            'bio_lo' => $this->bio_lo,
            'bio_en' => $this->bio_en,
            'education_lo' => $this->education_lo,
            'education_en' => $this->education_en,
            'date_of_birth' => $this->date_of_birth ?: null,
            'term_start' => $this->term_start,
            'term_end' => $this->term_end,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];

        $photo = $this->photo;

        if ($this->editMode) {
            $service->update($this->personnelId, $data, $photo);
            session()->flash('message', 'ແກ້ໄຂສຳເລັດແລ້ວ / Updated successfully.');
        } else {
            $service->create($data, $photo);
            session()->flash('message', 'ເພີ່ມສຳເລັດແລ້ວ / Created successfully.');
        }

        $this->redirect(route('personnel.index'), navigate: true);
    }

    public function render()
    {
        $departments = Department::active()->ordered()->get(['id', 'name_lo', 'name_en']);

        return view('livewire.personnel.form', [
            'departments' => $departments,
        ]);
    }
}
