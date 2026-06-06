<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'title_lo',
        'title_en',
        'excerpt_lo',
        'excerpt_en',
        'content_lo',
        'content_en',
        'cover_image',
        'published_at',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
    ];

    /* ───── Accessors ───── */

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'lo'
            ? ($this->title_lo ?? $this->title_en ?? '')
            : ($this->title_en ?? $this->title_lo ?? '');
    }

    public function getExcerptAttribute(): ?string
    {
        return app()->getLocale() === 'lo'
            ? ($this->excerpt_lo ?? $this->excerpt_en)
            : ($this->excerpt_en ?? $this->excerpt_lo);
    }

    public function getContentAttribute(): ?string
    {
        return app()->getLocale() === 'lo'
            ? ($this->content_lo ?? $this->content_en)
            : ($this->content_en ?? $this->content_lo);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }

    public function getPublishedDateFormattedAttribute(): string
    {
        return $this->published_at
            ? $this->published_at->format('d/m/Y')
            : '—';
    }

    /* ───── Relationships ───── */

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /* ───── Scopes ───── */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('created_at');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }
        return $query->where(function (Builder $q) use ($term) {
            $q->where('title_lo', 'like', "%{$term}%")
              ->orWhere('title_en', 'like', "%{$term}%")
              ->orWhere('excerpt_lo', 'like', "%{$term}%")
              ->orWhere('excerpt_en', 'like', "%{$term}%");
        });
    }
}
