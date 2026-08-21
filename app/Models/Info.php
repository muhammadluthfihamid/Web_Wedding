<?php

namespace App\Models;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasEncryptedRouteKey;

/**
 * @mixin Builder
 * @mixin Model
 */
class Info extends Model
{
    use HasFactory, SoftDeletes, HasEncryptedRouteKey;

    protected $fillable = [
        'user_id',
        'nama_pengantin_istri',
        'nama_pengantin_pria',
        'tanggal_pernikahan',
        'mulai_akad',
        'selesai_akad',
        'mulai_resepsi',
        'alamat',
        'deskripsi',
        'latitude',
        'longitude',
        'teks_arab',
        'salam_pembuka',
        'teks_pembuka',
        'teks_penutup',
        'salam_penutup',
        'musik_url',
        'is_audio_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'tanggal_pernikahan' => 'date:Y-m-d',
        'is_audio_active'    => 'boolean',
    ];
    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'id_nama_pengantin_pria');
    }

}