<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'personnel';

    protected $fillable = [
        'department_id', 'gender',
        'first_name_lo', 'first_name_en',
        'last_name_lo', 'last_name_en',
        'name_lo', 'name_en',
        'title_lo', 'title_en',
        'position_lo', 'position_en',
        'birth_village_lo', 'birth_village_en',
        'district_lo', 'district_en',
        'province_lo', 'province_en',
        'current_temple_lo', 'current_temple_en',
        'date_of_ordination', 'pansa',
        'facebook', 'photo_url', 'email', 'phone',
        'bio_lo', 'bio_en',
        'education_lo', 'education_en',
        'date_of_birth', 'term_start', 'term_end',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_ordination' => 'date',
        'is_active' => 'boolean',
    ];

    /* ───── Locale-Aware Accessors ───── */

    /**
     * Get full display name based on current locale.
     */
    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'lo'
            ? ($this->name_lo ?? $this->name_en ?? '')
            : ($this->name_en ?? $this->name_lo ?? '');
    }

    /**
     * Get position based on current locale.
     */
    public function getDisplayPositionAttribute(): string
    {
        return app()->getLocale() === 'lo'
            ? ($this->position_lo ?? $this->position_en ?? '')
            : ($this->position_en ?? $this->position_lo ?? '');
    }

    /**
     * Get title/honorific based on current locale.
     */
    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'lo'
            ? ($this->title_lo ?? $this->title_en ?? '')
            : ($this->title_en ?? $this->title_lo ?? '');
    }

    /**
     * Get biography based on current locale.
     */
    public function getDisplayBioAttribute(): ?string
    {
        return app()->getLocale() === 'lo'
            ? ($this->bio_lo ?? $this->bio_en)
            : ($this->bio_en ?? $this->bio_lo);
    }

    /* ───── Type Checks ───── */

    public function isMonk(): bool
    {
        return $this->gender === 'monk';
    }

    public function isMale(): bool
    {
        return $this->gender === 'male';
    }

    public function isFemale(): bool
    {
        return $this->gender === 'female';
    }

    /**
     * Get the badge color class for this person type.
     */
    public function getGenderBadgeAttribute(): array
    {
        return match ($this->gender) {
            'monk'   => ['label' => 'MONK', 'class' => 'bg-amber-600 text-white'],
            'male'   => ['label' => 'MALE', 'class' => 'bg-slate-800 text-white'],
            'female' => ['label' => 'FEMALE', 'class' => 'bg-violet-700 text-white'],
            default  => ['label' => 'N/A', 'class' => 'bg-gray-400 text-white'],
        };
    }

    /* ───── Relationships ───── */

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /* ───── Scopes ───── */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMonks($query)
    {
        return $query->where('gender', 'monk');
    }

    public function scopeLaypersons($query)
    {
        return $query->whereIn('gender', ['male', 'female']);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name_lo');
    }

    public function scopeSearch($query, ?string $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name_lo', 'LIKE', "%{$search}%")
              ->orWhere('name_en', 'LIKE', "%{$search}%")
              ->orWhere('position_lo', 'LIKE', "%{$search}%")
              ->orWhere('position_en', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%");
        });
    }

    /* ───── Auto-Generate Full Name ───── */

    /**
     * Build the full display name from title + first + last name.
     */
    public static function buildFullName(?string $title, ?string $firstName, ?string $lastName): string
    {
        return trim(implode(' ', array_filter([$title, $firstName, $lastName])));
    }
}
