<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('app_settings');
        });

        static::deleted(function () {
            Cache::forget('app_settings');
        });
    }

    public static function get(string $key, $default = null)
    {
        $settings = Cache::rememberForever('app_settings', function () {
            return static::all()->pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, $value, string $type = 'string', string $group = 'general', ?string $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (is_array($value) ? json_encode($value) : $value)]
        );
    }
}
