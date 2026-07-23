<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    private static array $cache = [];

    /**
     * Preload all settings into the in-memory cache with a single query.
     * Call this once per request (e.g. in AppServiceProvider::boot())
     * to avoid N+1 queries when Setting::get() is called in Blade templates.
     */
    public static function preloadAll(): void
    {
        if (!empty(self::$cache)) {
            return;
        }

        // Guard against boot happening before migrations run (e.g. RefreshDatabase in tests).
        if (!Schema::hasTable('settings')) {
            return;
        }

        static::all()->each(fn($setting) => self::$cache[$setting->key] = $setting->value);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        $setting = static::where('key', $key)->first();
        return self::$cache[$key] = $setting?->value ?? $default;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
        self::$cache[$key] = $value;
    }

    public static function setMany(array $data, string $group = 'general'): void
    {
        foreach ($data as $key => $value) {
            static::set($key, $value, $group);
        }
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    public static function clearCache(): void
    {
        self::$cache = [];
    }
}
