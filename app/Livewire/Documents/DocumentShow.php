<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Services\DocumentService;
use Livewire\Component;

class DocumentShow extends Component
{
    public Document $document;

    public function mount(int $id): void
    {
        $this->document = Document::with(['department', 'uploader'])->findOrFail($id);
    }

    public function delete(): void
    {
        app(DocumentService::class)->delete($this->document->id);
        session()->flash('message', 'ລຶບເອກະສານສຳເລັດ / Document deleted.');
        $this->redirect(route('documents.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.documents.show');
    }
}
