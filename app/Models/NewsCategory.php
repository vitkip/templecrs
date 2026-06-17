<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsCategory extends Model
{
    protected $fillable = [
        'slug',
        'name_lo',
        'name_en',
        'icon',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /* ───── Color map ───── */

    public static array $colorMap = [
        'red'    => 'text-red-700 bg-red-50 border-red-200',
        'blue'   => 'text-blue-700 bg-blue-50 border-blue-200',
        'amber'  => 'text-amber-700 bg-amber-50 border-amber-200',
        'green'  => 'text-green-700 bg-green-50 border-green-200',
        'purple' => 'text-purple-700 bg-purple-50 border-purple-200',
        'gray'   => 'text-gray-700 bg-gray-50 border-gray-200',
        'indigo' => 'text-indigo-700 bg-indigo-50 border-indigo-200',
        'teal'   => 'text-teal-700 bg-teal-50 border-teal-200',
        'rose'   => 'text-rose-700 bg-rose-50 border-rose-200',
        'orange' => 'text-orange-700 bg-orange-50 border-orange-200',
        'cyan'   => 'text-cyan-700 bg-cyan-50 border-cyan-200',
        'pink'   => 'text-pink-700 bg-pink-50 border-pink-200',
    ];

    public static array $colorOptions = [
        'red', 'blue', 'amber', 'green', 'purple', 'gray',
        'indigo', 'teal', 'rose', 'orange', 'cyan', 'pink',
    ];

    /* ───── Accessors ───── */

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'lo'
            ? ($this->name_lo ?? $this->name_en ?? '')
            : ($this->name_en ?? $this->name_lo ?? '');
    }

    public function getColorClassAttribute(): string
    {
        return self::$colorMap[$this->color] ?? self::$colorMap['blue'];
    }

    /* ───── Relationships ───── */

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'news_category_id');
    }

    /* ───── Scopes ───── */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name_lo');
    }
}
