<?php

namespace App\Models;

use App\Models\GalleryImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedRouteKey;

/**
 * @mixin Builder
 * @mixin Model
 */
class Gallery extends Model
{
    use HasFactory, HasEncryptedRouteKey;

    protected $fillable = [
        'user_id',
        'id_nama_pengantin_istri',
        'id_nama_pengantin_pria',
        'deskripsi',
    ];

    // Relasi ke pengantin istri
    public function istri()
    {
        return $this->belongsTo(Info::class, 'id_nama_pengantin_istri');
    }

    // Relasi ke pengantin pria
    public function pria()
    {
        return $this->belongsTo(Info::class, 'id_nama_pengantin_pria');
    }

    // Relasi ke gambar
    public function images()
    {
        return $this->hasMany(GalleryImage::class);
    }
}
