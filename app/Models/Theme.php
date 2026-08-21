<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 * @mixin Model
 * @method static Builder|Theme active()
 * @method static Builder|Theme wedding()
 * @method static Builder|Theme khitanan()
 */
class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'thumbnail',
        'blade_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get URL thumbnail tema secara dinamis
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        if (file_exists(public_path('storage/' . $this->thumbnail))) {
            return asset('storage/' . $this->thumbnail);
        }

        if (file_exists(public_path($this->thumbnail))) {
            return asset($this->thumbnail);
        }

        return asset('storage/' . $this->thumbnail);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWedding(Builder $query): Builder
    {
        return $query->where('category', 'wedding');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeKhitanan(Builder $query): Builder
    {
        return $query->where('category', 'khitanan');
    }
}
