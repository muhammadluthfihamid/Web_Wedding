<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasEncryptedRouteKey;

/**
 * @mixin Builder
 * @mixin Model
 */
class BiodataWanita extends Model
{
    use HasFactory, HasEncryptedRouteKey;
    protected $fillable = [
        'user_id',
        'nama',
        'ibu',
        'bapak',
        'foto',
        'deskripsi',
        'asal'
    ];
}
