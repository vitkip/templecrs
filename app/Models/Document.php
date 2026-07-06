<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'department_id',
        'uploaded_by',
        'title_lo',
        'title_en',
        'doc_number',
        'category',
        'description_lo',
        'description_en',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'issued_date',
        'sort_order',
        'is_active',
        'download_count',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'issued_date'    => 'date',
        'file_size'      => 'integer',
        'download_count' => 'integer',
    ];

    /* ───── Accessors ───── */

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'lo'
            ? ($this->title_lo ?? $this->title_en ?? '')
            : ($this->title_en ?? $this->title_lo ?? '');
    }

    private function getCategoryModel(): ?DocumentCategory
    {
        return DocumentCategory::where('slug', $this->category)->first();
    }

    public function getCategoryLabelAttribute(): string
    {
        $cat = $this->getCategoryModel();
        if (!$cat) return $this->category;
        return app()->getLocale() === 'lo' ? $cat->name_lo : ($cat->name_en ?? $cat->name_lo);
    }

    public function getCategoryIconAttribute(): string
    {
        return $this->getCategoryModel()?->icon ?? 'description';
    }

    public function getCategoryColorAttribute(): string
    {
        return $this->getCategoryModel()?->color_class ?? 'text-gray-700 bg-gray-50 border-gray-200';
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return '—';
        }
        $kb = $this->file_size / 1024;
        if ($kb < 1024) {
            return number_format($kb, 1) . ' KB';
        }
        return number_format($kb / 1024, 2) . ' MB';
    }

    public function getFileIconAttribute(): string
    {
        return match (true) {
            str_contains($this->file_type ?? '', 'pdf')                               => 'picture_as_pdf',
            str_contains($this->file_type ?? '', 'word') || str_ends_with($this->file_name ?? '', '.docx') => 'article',
            str_contains($this->file_type ?? '', 'sheet') || str_ends_with($this->file_name ?? '', '.xlsx') => 'table_chart',
            str_contains($this->file_type ?? '', 'image')                             => 'image',
            default                                                                    => 'attach_file',
        };
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? route('documents.download', $this->id) : null;
    }

    /* ───── Relationships ───── */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /* ───── Scopes ───── */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('issued_date')->orderByDesc('created_at');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }
        return $query->where(function (Builder $q) use ($term) {
            $q->where('title_lo', 'like', "%{$term}%")
              ->orWhere('title_en', 'like', "%{$term}%")
              ->orWhere('doc_number', 'like', "%{$term}%")
              ->orWhere('description_lo', 'like', "%{$term}%")
              ->orWhere('description_en', 'like', "%{$term}%");
        });
    }

    public function scopeYear(Builder $query, int|string|null $year): Builder
    {
        if (!$year) {
            return $query;
        }
        return $query->whereYear('issued_date', $year);
    }
}
