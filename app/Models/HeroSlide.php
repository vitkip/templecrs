<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title_lo',
        'title_en',
        'subtitle_lo',
        'subtitle_en',
        'image_path',
        'button_link',
        'button_text_lo',
        'button_text_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ───── Accessors ───── */

    public function getTitleAttribute(): ?string
    {
        return app()->getLocale() === 'lo'
            ? ($this->title_lo ?? $this->title_en)
            : ($this->title_en ?? $this->title_lo);
    }

    public function getSubtitleAttribute(): ?string
    {
        return app()->getLocale() === 'lo'
            ? ($this->subtitle_lo ?? $this->subtitle_en)
            : ($this->subtitle_en ?? $this->subtitle_lo);
    }

    public function getButtonTextAttribute(): ?string
    {
        return app()->getLocale() === 'lo'
            ? ($this->button_text_lo ?? $this->button_text_en)
            : ($this->button_text_en ?? $this->button_text_lo);
    }

    public function getImageUrlAttribute(): string
    {
        return Storage::url($this->image_path);
    }

    /* ───── Scopes ───── */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('created_at');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }
        return $query->where(function (Builder $q) use ($term) {
            $q->where('title_lo', 'like', "%{$term}%")
              ->orWhere('title_en', 'like', "%{$term}%")
              ->orWhere('subtitle_lo', 'like', "%{$term}%")
              ->orWhere('subtitle_en', 'like', "%{$term}%");
        });
    }
}
