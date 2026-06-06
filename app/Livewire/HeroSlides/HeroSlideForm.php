<?php

namespace App\Livewire\HeroSlides;

use App\Models\HeroSlide;
use App\Services\HeroSlideService;
use Livewire\Component;
use Livewire\WithFileUploads;

class HeroSlideForm extends Component
{
    use WithFileUploads;

    public bool $editMode   = false;
    public ?int $slideId    = null;

    // Fields
    public ?string $title_lo = null;
    public ?string $title_en = null;
    public ?string $subtitle_lo = null;
    public ?string $subtitle_en = null;

    public $image = null;
    public ?string $existing_image_path = null;

    public ?string $button_link = null;
    public ?string $button_text_lo = null;
    public ?string $button_text_en = null;

    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->editMode = true;
            $this->slideId  = $id;
            $this->loadSlide($id);
        }
    }

    private function loadSlide(int $id): void
    {
        $slide = HeroSlide::findOrFail($id);

        $this->title_lo      = $slide->title_lo;
        $this->title_en      = $slide->title_en;
        $this->subtitle_lo   = $slide->subtitle_lo;
        $this->subtitle_en   = $slide->subtitle_en;
        $this->button_link   = $slide->button_link;
        $this->button_text_lo = $slide->button_text_lo;
        $this->button_text_en = $slide->button_text_en;
        $this->sort_order    = $slide->sort_order ?? 0;
        $this->is_active     = $slide->is_active ?? true;
        $this->existing_image_path = $slide->image_path;
    }

    protected function rules(): array
    {
        return [
            'title_lo'       => 'nullable|string|max:500',
            'title_en'       => 'nullable|string|max:500',
            'subtitle_lo'    => 'nullable|string|max:1000',
            'subtitle_en'    => 'nullable|string|max:1000',
            'image'          => ($this->editMode ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp|max:10240',
            'button_link'    => 'nullable|string|max:500',
            'button_text_lo' => 'nullable|string|max:100',
            'button_text_en' => 'nullable|string|max:100',
            'sort_order'     => 'nullable|integer|min:0',
            'is_active'      => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'image.required' => 'ກະລຸນາເລືອກຮູບພາບພື້ນຫຼັງ',
            'image.image'    => 'ກະລຸນາເລືອກໄຟລ໌ຮູບພາບທີ່ຖືກຕ້ອງ',
            'image.max'      => 'ຮູບພາບຕ້ອງບໍ່ເກີນ 10MB',
            'image.mimes'    => 'ຮອງຮັບໄຟລ໌ JPG, JPEG, PNG, WEBP',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $service = app(HeroSlideService::class);

        $data = [
            'title_lo'       => $this->title_lo,
            'title_en'       => $this->title_en,
            'subtitle_lo'    => $this->subtitle_lo,
            'subtitle_en'    => $this->subtitle_en,
            'button_link'    => $this->button_link,
            'button_text_lo' => $this->button_text_lo,
            'button_text_en' => $this->button_text_en,
            'sort_order'     => $this->sort_order,
            'is_active'      => $this->is_active,
        ];

        if ($this->editMode) {
            $service->update($this->slideId, $data, $this->image);
            session()->flash('message', 'ແກ້ໄຂຮູບສະໄລ້ສຳເລັດ / Slide updated.');
        } else {
            $service->create($data, $this->image);
            session()->flash('message', 'ເພີ່ມຮູບສະໄລ້ສຳເລັດ / Slide created.');
        }

        $this->redirect(route('hero-slides.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.hero-slides.form');
    }
}
