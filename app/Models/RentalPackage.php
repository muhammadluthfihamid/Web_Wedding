<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasEncryptedRouteKey;

/**
 * @mixin Builder
 * @mixin Model
 * @method static Builder|RentalPackage aktif()
 */
class RentalPackage extends Model
{
    use HasFactory, HasEncryptedRouteKey;

    protected $fillable = [
        'nama', 'deskripsi', 'harga', 'durasi_hari',
        'fitur', 'is_aktif', 'warna_badge', 'is_populer',
    ];

    protected $casts = [
        'fitur'      => 'array',
        'is_aktif'   => 'boolean',
        'is_populer' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Format harga ke Rupiah.
     */
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Format durasi ke teks bulan.
     */
    public function getDurasiTeksAttribute(): string
    {
        $bulan = round($this->durasi_hari / 30);
        return $bulan . ' Bulan';
    }

    /**
     * Scope: hanya paket aktif.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }
}
