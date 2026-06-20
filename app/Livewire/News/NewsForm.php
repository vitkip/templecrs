<?php

namespace App\Livewire\News;

use App\Models\News;
use App\Models\NewsCategory;
use App\Services\NewsService;
use Livewire\Component;
use Livewire\WithFileUploads;

class NewsForm extends Component
{
    use WithFileUploads;

    public bool $editMode   = false;
    public ?int $newsId     = null;

    // Category
    public ?int $news_category_id = null;

    // Title
    public string $title_lo  = '';
    public ?string $title_en = null;

    // Excerpt
    public ?string $excerpt_lo = null;
    public ?string $excerpt_en = null;

    // Content
    public ?string $content_lo = null;
    public ?string $content_en = null;

    // Cover Image
    public $cover_image = null;
    public ?string $existing_cover_image = null;

    // Publishing
    public ?string $published_at = null;
    public bool $is_featured     = false;

    // Display
    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $id = null): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageNews(), 403);

        if ($id) {
            $this->editMode = true;
            $this->newsId   = $id;
            $this->loadNews($id);
        }
    }

    private function loadNews(int $id): void
    {
        $news = News::findOrFail($id);

        $this->news_category_id = $news->news_category_id;
        $this->title_lo         = $news->title_lo;
        $this->title_en         = $news->title_en;
        $this->excerpt_lo     = $news->excerpt_lo;
        $this->excerpt_en     = $news->excerpt_en;
        $this->content_lo     = $news->content_lo;
        $this->content_en     = $news->content_en;
        $this->published_at   = $news->published_at?->format('Y-m-d\TH:i');
        $this->is_featured    = $news->is_featured ?? false;
        $this->sort_order     = $news->sort_order ?? 0;
        $this->is_active      = $news->is_active ?? true;
        $this->existing_cover_image = $news->cover_image;
    }

    protected function rules(): array
    {
        return [
            'news_category_id' => 'nullable|exists:news_categories,id',
            'title_lo'     => 'required|string|max:500',
            'title_en'     => 'nullable|string|max:500',
            'excerpt_lo'   => 'nullable|string|max:1000',
            'excerpt_en'   => 'nullable|string|max:1000',
            'content_lo'   => 'nullable|string',
            'content_en'   => 'nullable|string',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'published_at' => 'nullable|date',
            'is_featured'  => 'boolean',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
        ];
    }

    protected function messages(): array
    {
        return [
            'title_lo.required'  => 'ກະລຸນາໃສ່ຫົວຂໍ້ຂ່າວ (ພາສາລາວ)',
            'cover_image.max'    => 'ຮູບປົກຕ້ອງບໍ່ເກີນ 10MB',
            'cover_image.image'  => 'ກະລຸນາເລືອກໄຟລ໌ຮູບພາບ',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $service = app(NewsService::class);

        $data = [
            'news_category_id' => $this->news_category_id ?: null,
            'title_lo'     => $this->title_lo,
            'title_en'     => $this->title_en,
            'excerpt_lo'   => $this->excerpt_lo,
            'excerpt_en'   => $this->excerpt_en,
            'content_lo'   => $this->content_lo,
            'content_en'   => $this->content_en,
            'published_at' => $this->published_at ?: null,
            'is_featured'  => $this->is_featured,
            'sort_order'   => $this->sort_order,
            'is_active'    => $this->is_active,
        ];

        if ($this->editMode) {
            $service->update($this->newsId, $data, $this->cover_image);
            session()->flash('message', 'ແກ້ໄຂຂ່າວສຳເລັດ / News updated.');
        } else {
            $service->create($data, $this->cover_image);
            session()->flash('message', 'ເພີ່ມຂ່າວສຳເລັດ / News created.');
        }

        $this->redirect(route('news.index'), navigate: true);
    }

    public function render()
    {
        $categories = NewsCategory::active()->ordered()->get();

        return view('livewire.news.form', compact('categories'));
    }
}
