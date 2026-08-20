<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'label', 'description'];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting)
            return $default;

        return match ($setting->type) {
                'boolean' => (bool)$setting->value,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
        ['key' => $key],
        ['value' => is_array($value) ? json_encode($value) : $value]
        );
    }

    /**
     * Get all settings for a group as key-value pairs.
     */
    public static function group(string $group): array
    {
        return static::where('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }
}
