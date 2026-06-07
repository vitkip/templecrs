<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Document;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name_lo',
        'name_en',
        'description_lo',
        'description_en',
        'head_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ───── Locale-Aware Accessors ───── */

    /**
     * Get department name based on current locale.
     */
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'lo'
            ? ($this->name_lo ?? $this->name_en ?? '')
            : ($this->name_en ?? $this->name_lo ?? '');
    }

    /**
     * Get department description based on current locale.
     */
    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'lo'
            ? ($this->description_lo ?? $this->description_en)
            : ($this->description_en ?? $this->description_lo);
    }

    /* ───── Relationships ───── */

    /**
     * Department head — can be a monk or layperson.
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'head_id');
    }

    /**
     * All personnel in this department.
     */
    public function personnel(): HasMany
    {
        return $this->hasMany(Personnel::class);
    }

    /**
     * All documents in this department.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /* ───── Scopes ───── */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_lo');
    }

    /* ───── Helpers ───── */

    /**
     * Get personnel count for this department.
     */
    public function getPersonnelCountAttribute(): int
    {
        return $this->personnel()->active()->count();
    }
}
