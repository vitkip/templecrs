<?php

namespace App\Livewire\News;

use App\Models\News;
use App\Services\NewsService;
use Livewire\Component;

class NewsShow extends Component
{
    public News $news;

    public function mount(int $id): void
    {
        $this->news = News::with('author')->findOrFail($id);
    }

    public function delete(): void
    {
        app(NewsService::class)->delete($this->news->id);
        session()->flash('message', 'ລຶບຂ່າວສຳເລັດ / News deleted.');
        $this->redirect(route('news.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.news.show');
    }
}
