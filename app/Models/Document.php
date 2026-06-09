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

    /* ───── Category Metadata ───── */

    public static array $categories = [
        'order'        => ['lo' => 'ຄຳສັ່ງ',        'en' => 'Order / Directive',  'icon' => 'gavel',        'color' => 'text-red-700 bg-red-50 border-red-200'],
        'announcement' => ['lo' => 'ແຈ້ງການ',       'en' => 'Announcement',       'icon' => 'campaign',     'color' => 'text-blue-700 bg-blue-50 border-blue-200'],
        'certificate'  => ['lo' => 'ໃບຢັ້ງຢືນ',    'en' => 'Certificate',        'icon' => 'workspace_premium', 'color' => 'text-amber-700 bg-amber-50 border-amber-200'],
        'report'       => ['lo' => 'ລາຍງານ',        'en' => 'Report',             'icon' => 'assessment',   'color' => 'text-green-700 bg-green-50 border-green-200'],
        'project'      => ['lo' => 'ໂຄງການ',        'en' => 'Project Document',   'icon' => 'folder_special','color' => 'text-purple-700 bg-purple-50 border-purple-200'],
        'other'        => ['lo' => 'ອື່ນໆ',          'en' => 'Other',              'icon' => 'description',  'color' => 'text-gray-700 bg-gray-50 border-gray-200'],
    ];

    /* ───── Accessors ───── */

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'lo'
            ? ($this->title_lo ?? $this->title_en ?? '')
            : ($this->title_en ?? $this->title_lo ?? '');
    }

    public function getCategoryLabelAttribute(): string
    {
        $meta = self::$categories[$this->category] ?? self::$categories['other'];
        return app()->getLocale() === 'lo' ? $meta['lo'] : $meta['en'];
    }

    public function getCategoryIconAttribute(): string
    {
        return self::$categories[$this->category]['icon'] ?? 'description';
    }

    public function getCategoryColorAttribute(): string
    {
        return self::$categories[$this->category]['color'] ?? self::$categories['other']['color'];
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
}
