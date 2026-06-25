<?php

namespace App\Livewire;

use App\Models\Document;
use App\Models\FinanceTransaction;
use App\Models\News;
use App\Models\Personnel;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    public array $results = [];

    public bool $open = false;

    public function updatedQuery(): void
    {
        $q = trim($this->query);

        if (strlen($q) < 2) {
            $this->results = [];
            $this->open    = false;
            return;
        }

        $user    = auth()->user();
        $results = [];

        if ($user->canManagePersonnel()) {
            $rows = Personnel::where('is_active', true)
                ->where(fn ($sq) =>
                    $sq->where('name_lo', 'like', "%{$q}%")
                       ->orWhere('name_en', 'like', "%{$q}%")
                       ->orWhere('first_name_lo', 'like', "%{$q}%")
                       ->orWhere('last_name_lo', 'like', "%{$q}%")
                       ->orWhere('first_name_en', 'like', "%{$q}%")
                       ->orWhere('last_name_en', 'like', "%{$q}%")
                )
                ->limit(5)->get();

            foreach ($rows as $p) {
                $results[] = [
                    'type'     => 'personnel',
                    'icon'     => 'person',
                    'color'    => 'text-primary',
                    'bg'       => 'bg-primary/10',
                    'label'    => $p->display_name,
                    'sub'      => $p->display_position ?: null,
                    'url'      => route('personnel.show', $p->id),
                ];
            }
        }

        if ($user->canManageNews()) {
            $rows = News::where('is_active', true)
                ->where(fn ($sq) =>
                    $sq->where('title_lo', 'like', "%{$q}%")
                       ->orWhere('title_en', 'like', "%{$q}%")
                )
                ->latest('published_at')->limit(5)->get();

            foreach ($rows as $n) {
                $results[] = [
                    'type'  => 'news',
                    'icon'  => 'newspaper',
                    'color' => 'text-secondary',
                    'bg'    => 'bg-secondary/10',
                    'label' => $n->title,
                    'sub'   => $n->published_at?->format('d/m/Y'),
                    'url'   => route('news.show', $n->id),
                ];
            }
        }

        if ($user->canManageDocuments()) {
            $rows = Document::where('is_active', true)
                ->where(fn ($sq) =>
                    $sq->where('title_lo', 'like', "%{$q}%")
                       ->orWhere('title_en', 'like', "%{$q}%")
                )
                ->latest()->limit(5)->get();

            foreach ($rows as $d) {
                $results[] = [
                    'type'  => 'document',
                    'icon'  => $d->icon ?? 'description',
                    'color' => 'text-tertiary',
                    'bg'    => 'bg-tertiary/10',
                    'label' => $d->title_lo ?? $d->title_en,
                    'sub'   => null,
                    'url'   => route('documents.show', $d->id),
                ];
            }
        }

        if ($user->canManageFinance()) {
            $rows = FinanceTransaction::with('category')
                ->where(fn ($sq) =>
                    $sq->where('description', 'like', "%{$q}%")
                       ->orWhereHas('category', fn ($cq) =>
                           $cq->where('name_lo', 'like', "%{$q}%")
                              ->orWhere('name_en', 'like', "%{$q}%")
                       )
                )
                ->latest('transaction_date')->limit(5)->get();

            foreach ($rows as $tx) {
                $results[] = [
                    'type'  => 'finance',
                    'icon'  => $tx->type === 'income' ? 'arrow_downward' : 'arrow_upward',
                    'color' => $tx->type === 'income' ? 'text-success' : 'text-error',
                    'bg'    => $tx->type === 'income' ? 'bg-success/10' : 'bg-error/10',
                    'label' => $tx->description ?: ($tx->category->name ?? '—'),
                    'sub'   => $tx->transaction_date_formatted ?? null,
                    'url'   => route('finance.index'),
                ];
            }
        }

        $this->results = $results;
        $this->open    = count($results) > 0;
    }

    public function clear(): void
    {
        $this->query   = '';
        $this->results = [];
        $this->open    = false;
    }

    public function render()
    {
        return view('livewire.global-search');
    }
}
