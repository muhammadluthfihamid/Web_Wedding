<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * @mixin Builder
 * @mixin Model
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key with cache support.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        try {
            if (!Schema::hasTable('settings')) {
                return $default;
            }
            return Cache::remember('setting_' . $key, 3600, function () use ($key, $default) {
                $setting = static::where('key', $key)->first();
                return ($setting && !is_null($setting->value) && $setting->value !== '') ? $setting->value : $default;
            });
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Set/update a setting value by key and clear cache.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('setting_' . $key);
    }

    /**
     * Helper to retrieve store/brand name.
     */
    public static function getStoreName(): string
    {
        return static::get('store_name', "Lu'iz-Wedding");
    }
}
